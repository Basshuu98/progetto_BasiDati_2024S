CREATE OR REPLACE FUNCTION check_numeroprestiti() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE 
            _in_corso integer;
            _counter smallint;
            _tipo TIPO_LETTORE;
            _disponibile;
     BEGIN
          SET search_pat TO unibib;

          --confronto il numero di ritardi dalla tabella lettori
          SELECT l.ritardi, l.categoria INTO _counter, _tipo
          FROM lettore AS l
          WHERE l.cod_fiscale = NEW.cod_fiscale
          
          --controllo la disponibilità di un libro
          SELECT li.disponibilità INTO _disponibile
          FROM libri AS li
          WHERE li.ISBN = NEW.ISBN AND li.id = NEW.copia_id

          IF _disponibile = false THEN 
               RAISE EXCEPTION 'Il libro non è disponibile';
          END IF;
          
          IF _counter > 4 THEN
               RAISE EXCEPTION 'Raggiunto il massimo numero di consegne in ritardo, contattare la segreteria.';
          END IF;
          
          -- controllo il numero di prestiti in corso del lettore
          SELECT COUNT(*) INTO _in_corso
          FROM prestiti AS p
          WHERE p.cod_fiscale = NEW.cod_fiscale AND data_consegna IS NULL

          IF _tipo = 'Base' AND _in_corso > 2 
               RAISE EXCEPTION 'Raggiunto il numero massimo di prestiti contemporanei per il piano base, passare al piano premium'

          END IF;
          

          IF _tipo = 'Premium' AND _in_corso > 4
               RAISE EXCEPTION 'Raggiunto il numero massimo di prestiti contemporanei permesso'
          END IF;

        RETURN NEW;
     END;
$$

--trigger che controlla che sia possibile effettuare il prestito 
--considerando i vari casi: 1. Il libro non è disponibile,
--                          2. Raggiunte 5 riconsegne in ritardo
--                          3. Superato il limite di prestiti in 
--                             contemporanea in base al piano

CREATE OR REPLACE TRIGGER i_u_check_prestito
   BEFORE INSERT OR UPDATE ON prestiti
   FOR EACH ROW
   EXECUTE PROCEDURE check_numeroprestiti();

CREATE OR REPLACE FUNCTION check_morte_autore() RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
     DECLARE 
          _nascita date;
     BEGIN
       SET search_pat TO unibib;
       -- controllo che se la data di morte è stata inserita, essa non sia prima della data di nascita
       SELECT a.data_nascita INTO _nascita
       FROM autori AS a
       WHERE a.id = NEW.id
       
       IF NEW.data_morte IS NOT NULL AND _nascita < NEW.data_morte
          RAISE EXCEPTION 'La data di morte non può essere prima della data di nascita'
       END IF;

       RETURN NEW;
     END;
$$

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
       WHERE p.ISBN = OLD.ISBN AND p.stato = 'In corso'

       IF _count > 0 THEN
          RAISE EXCEPTION 'Non è possibile eliminare libri che sono ancora in prestito'
       END IF;

       RETURN OLD;
     END;
$$

CREATE OR REPLACE TRIGGER d_libro
   BEFORE DELETE ON libri
   FOR EACH ROW
   EXECUTE PROCEDURE check_fine_prestito();


CREATE OR REPLACE FUNCTION check_nessun_prestito() RETURNS TRIGGER
LANGUAGE
AS $$
     DECLARE
       _count integer;
     BEGIN
       SET search_path TO unibib;
       --controlla che l'utente non abbia prestiti in corso
       SELECT COUNT(*) INTO _count
       FROM prestiti AS p
       WHERE p.cod_fiscale = OLD.cod_fiscale AND  p.stato = 'In corso'
       
       IF _count > 0 THEN
          RAISE EXCEPTION 'Impossibile eliminare utenti con prestiti in corso'
       END IF;
     END;
$$;

CREATE OR REPLACE TRIGGER d_lettore
   BEFORE DELETE ON lettori
   FOR EACH ROW
   EXECUTE PROCEDURE check_nessun_prestito();