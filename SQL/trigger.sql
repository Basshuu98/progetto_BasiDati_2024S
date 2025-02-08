
CREATE OR REPLACE FUNCTION check_numeroprestiti() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE 
            _in_corso integer;
            _in_ritardo integer;
            _counter smallint;
            _tipo TIPO_LETTORE;
            _disponibile bool;
     BEGIN
          SET search_path TO unibib;

          --confronto il numero di ritardi dalla tabella lettori
          SELECT l.ritardi, l.categoria INTO _counter, _tipo
          FROM lettori AS l
          WHERE l.cod_fiscale = NEW.cod_fiscale;
          
          --controllo la disponibilità di un libro
          SELECT li.disponibilita INTO _disponibile
          FROM libri AS li
          WHERE li.isbn = NEW.isbn AND li.id = NEW.copia_id;

          IF NEW.data_inizio > NEW.data_fine THEN
                RAISE EXCEPTION 'La data di fine prestito non può essere prima della data di inizio';
          END IF;

          IF _disponibile = false THEN 
               RAISE EXCEPTION 'Il libro non è disponibile';
          END IF;
          
          SELECT COUNT(*) INTO _in_ritardo
          FROM prestiti AS p
          WHERE p.cod_fiscale = NEW.cod_fiscale AND p.data_fine < CURRENT_DATE;

          IF (_counter + _in_ritardo) > 4 THEN
               RAISE EXCEPTION 'Raggiunto il massimo numero di consegne in ritardo, contattare la segreteria.';
          END IF;
          
          -- controllo il numero di prestiti in corso del lettore
          SELECT COUNT(*) INTO _in_corso
          FROM prestiti AS p
          WHERE p.cod_fiscale = NEW.cod_fiscale;

          IF _tipo = 'Base' AND _in_corso > 2 THEN
               RAISE EXCEPTION 'Raggiunto il numero massimo di prestiti contemporanei per il piano base, passare al piano premium';
          END IF;
          

          IF _tipo = 'Premium' AND _in_corso > 4 THEN
               RAISE EXCEPTION 'Raggiunto il numero massimo di prestiti contemporanei permesso';
          END IF;

        

          RETURN NEW;
     END;
$$;

--trigger che controlla che sia possibile effettuare il prestito 
--considerando i vari casi: 1. La data di fine sia prima della data d'inizio,
--                          2. Il libro non è disponibile,
--                          3. Raggiunte 5 riconsegne in ritardo
--                          4. Superato il limite di prestiti in 
--                             contemporanea in base al piano

CREATE OR REPLACE TRIGGER i_check_prestito
   BEFORE INSERT ON prestiti
   FOR EACH ROW
   EXECUTE PROCEDURE check_numeroprestiti();

CREATE OR REPLACE FUNCTION check_data_prestito() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
    DECLARE
        _data_inizio date;
        _data_fine date;
    BEGIN
        SET search_path TO unibib;

        --confronto la data di fine non sia prima della data di inizio oppure della data odierna
        SELECT data_inizio, data_fine INTO _data_inizio, _data_fine
        FROM prestiti
        WHERE id = NEW.id;

        IF _data_fine < CURRENT_DATE THEN
            RAISE EXCEPTION 'Non si può cambiare la data di un prestito già in ritardo';
        END IF;

        IF NEW.data_fine < _data_inizio OR NEW.data_fine < CURRENT_DATE THEN
            RAISE EXCEPTION 'La data di fine non puo essere nel passato';
        END IF;

        RETURN NEW;
    END
$$;

--trigger che controlla la data di fine prestito nel caso si decida di modificarla (es. proroga)
--considerando che la data di fine non può essere nel passato o prima della data di inizio del prestito
CREATE OR REPLACE TRIGGER u_check_prestito
   BEFORE UPDATE ON prestiti 
   FOR EACH ROW
   EXECUTE PROCEDURE check_data_prestito();

CREATE OR REPLACE FUNCTION check_morte_autore() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE 
          _nascita date;
     BEGIN
       SET search_path TO unibib;
       -- controllo che se la data di morte è stata inserita, essa non sia prima della data di nascita
       SELECT a.data_nascita INTO _nascita
       FROM autori AS a
       WHERE a.id = NEW.id;
       
       IF NEW.data_morte IS NOT NULL AND (_nascita > NEW.data_morte OR NEW.data_morte > CURRENT_DATE) THEN
          RAISE EXCEPTION 'La data di morte non può essere prima della data di nascita o nel futuro';
       END IF;

       RETURN NEW;
     END;
$$;

--trigger che controlla la data di morte quando la si inserisce per un autore
--controlla soprattutto che non sia prima della data di nascita o nel futuro
CREATE OR REPLACE TRIGGER i_u_check_autore 
   BEFORE INSERT OR UPDATE ON autori
   FOR EACH ROW
   EXECUTE PROCEDURE check_morte_autore();

CREATE OR REPLACE FUNCTION check_fine_prestito() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE
       _count integer;
     BEGIN
       SET search_path TO unibib;
       --controlla che tutti i prestiti del libro siano finiti
       SELECT COUNT(*) INTO _count
       FROM prestiti AS p
       WHERE p.isbn = OLD.isbn;

       IF _count > 0 THEN
          RAISE EXCEPTION 'Non è possibile eliminare libri che sono ancora in prestito';
       END IF;

       RETURN OLD;
     END;
$$;

--trigger che controlla se tutti i libri sono nelle sedi per poter eliminare quel libro e le relative copie 
CREATE OR REPLACE TRIGGER d_libro
   BEFORE DELETE ON cod_libro
   FOR EACH ROW
   EXECUTE PROCEDURE check_fine_prestito();

CREATE OR REPLACE FUNCTION check_fine_prestito_copia() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE
       _count integer;
     BEGIN
       SET search_path TO unibib;
       --controlla che il prestito della copia sia finito
       SELECT COUNT(*) INTO _count
       FROM prestiti AS p
       WHERE p.isbn = OLD.isbn AND p.copia_id = OLD.id;

       IF _count > 0 THEN
          RAISE EXCEPTION 'Non è possibile eliminare libri che sono ancora in prestito';
       END IF;

       RETURN OLD;
     END;
$$;

--trigger che controlla che la copia sia in gestione della sede per poterla eliminare
CREATE OR REPLACE TRIGGER d_copia
   BEFORE DELETE ON libri
   FOR EACH ROW
   EXECUTE PROCEDURE check_fine_prestito_copia();

CREATE OR REPLACE FUNCTION check_nessun_prestito() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE
       _count integer;
     BEGIN
       SET search_path TO unibib;
       --controlla che l'utente non abbia prestiti in corso
       SELECT COUNT(*) INTO _count
       FROM prestiti AS p
       WHERE p.cod_fiscale = OLD.cod_fiscale;
       
       IF _count > 0 THEN
          RAISE EXCEPTION 'Impossibile eliminare utenti con prestiti in corso';
       END IF;

       RETURN OLD;
     END;
$$;

--trigger che controlla che il lettore non abbia prestiti in corso per poterlo eliminare
CREATE OR REPLACE TRIGGER d_lettore
   BEFORE DELETE ON lettori
   FOR EACH ROW
   EXECUTE PROCEDURE check_nessun_prestito();