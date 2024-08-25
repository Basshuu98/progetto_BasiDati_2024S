--aggiunge un nuovo lettore, aggiungendo anche le credenziali in utenti
CREATE OR REPLACE PROCEDURE add_reader(
    _c_f char(16),
    _email text,
    _password text,
    _nome text,
    _cognome text,
    _cat TIPO_LETTORE
)
LANGUAGE plpgsql
AS $$
     DECLARE
       _id uuid;
     BEGIN
       SET search_path TO unibib;

       --inserimento delle credenziali nella tabella utenti, ricevendo l'id creato
       INSERT INTO utenti(email, password, nome, cognome) VALUES (_email, crypt(_password, gen_salt('bf')), _nome, _cognome) RETURNING id INTO _id;
       --inserimento delle credenziali nella tabella lettori
       INSERT INTO lettori(cod_fiscale, id, categoria) VALUES (_c_f, _id, _cat);
     END;
$$;
--modifica i dati di un lettore inserendo il suo Codice Fiscale
CREATE OR REPLACE PROCEDURE edit_reader(
	_c_f char(16),
    _email text,
    _nome text,
    _cognome text,
    _categoria TIPO_LETTORE,
    _ritardi smallint
)
LANGUAGE plpgsql
AS $$
     DECLARE 
       _id uuid;
     BEGIN
       SET search_path TO unibib;
       
       SELECT id INTO _id
       FROM lettori 
       WHERE cod_fiscale = _c_f;
       --modifico le informazioni del tipo nome,cognome e mail 
       UPDATE utenti SET
          email = _email,
          nome = _nome,
          cognome = _cognome
       WHERE id = _id;
       
       --modifico le informazioni del tipo categoria
       UPDATE lettori SET 
          categoria = _categoria,
          ritardi = _ritardi
       WHERE cod_fiscale = _c_f;    
     END;
$$;

--elimina un lettore inserendo il suo Codice Fiscale
CREATE OR REPLACE PROCEDURE delete_reader(
    _c_f char(16)
)
LANGUAGE plpgsql
AS $$
     DECLARE
       _id uuid;
     BEGIN
       SET search_path TO unibib;
       
       SELECT id INTO _id
       FROM lettori
       WHERE cod_fiscale = _c_f;
       
       -- elimino dalla tabella lettori 
       DELETE FROM lettori 
       WHERE cod_fiscale = _c_f;
       
       -- elimino dalla tabella utenti
       DELETE FROM utenti
       WHERE id = _id; 
     END;
$$;
--aggiunge un nuovo bibliotecario, aggiungendo anche le credenziali in utenti
CREATE OR REPLACE PROCEDURE add_librarian(
    _email text,
    _password text,
    _nome text,
    _cognome text
)
LANGUAGE plpgsql
AS $$
     DECLARE
       _id uuid;
     BEGIN
       SET search_path TO unibib;

       --inserimento delle credenziali nella tabella utenti, ricevendo l'id creato
       INSERT INTO utenti(email, password, nome, cognome) VALUES (_email, crypt(_password, gen_salt('bf')), _nome, _cognome) RETURNING id INTO _id;
       --inserimento del bibliotecario nella tabella bibliotecari
       INSERT INTO bibliotecari(id) VALUES (_id);
     END;
$$;

-- modifica la password di un utente come ruolo di admin e quindi non richiede la verifica
CREATE OR REPLACE PROCEDURE edit_password_as_admin(
    _id uuid, 
    _nuova_password text
) 
LANGUAGE plpgsql
AS $$
     BEGIN    
        SET search_path TO unibib;

        -- aggiorna la password
        UPDATE utenti SET password = crypt(_nuova_password, gen_salt('bf'))
        WHERE id = _id;

        IF NOT FOUND THEN
            raise exception 'Utente non trovato'; 
        END IF;
     END;
$$;

-- modifica la password di un utente come utente normale e quindi svolge la verifica con la vecchia password
CREATE OR REPLACE PROCEDURE edit_password_user(
    _id uuid, 
    _vecchia_password text,
    _nuova_password text
) 
LANGUAGE plpgsql
AS $$
     BEGIN    
        SET search_path TO unibib;

        -- aggiorna la password
        UPDATE utenti SET password = crypt(_nuova_password, gen_salt('bf'))
        WHERE id = _id AND password = crypt(_vecchia_password, password);

        IF NOT FOUND THEN
            raise exception 'Vecchia password errata'; 
        END IF;
     END;
$$;

--aggiunge una nuova sede
CREATE OR REPLACE PROCEDURE add_library(
    _città varchar(50),
    _indirizzo varchar(100)
)
LANGUAGE plpgsql
AS $$
     BEGIN
        SET search_path TO unibib;

        INSERT INTO sedi(città, indirizzo) VALUES(_città, _indirizzo);

     END;
$$;

--modifica una sede dato il suo id
CREATE OR REPLACE PROCEDURE edit_library(
    _id uuid,
    _città varchar(50),
    _indirizzo varchar(100)
)
LANGUAGE plpgsql
AS $$
     BEGIN
        SET search_path TO unibib;

        UPDATE sedi SET
            città = _città,
            indirizzo = _indirizzo 
        WHERE id = _id;
     END;
$$;


--aggiunge un nuovo libro (con i suo eventuali autori)
CREATE OR REPLACE PROCEDURE add_book(
    _isbn varchar(20),
    _sede uuid,
    _titolo text,
    _trama text,
    _autori uuid[]
)
LANGUAGE plpgsql
AS $$
     DECLARE
       _autore uuid;
     BEGIN
        SET search_path TO unibib;
         
        --inserisce i dati nella tabella cod_libro   
        INSERT INTO cod_libro(isbn, titolo, trama) VALUES (_isbn, _titolo, _trama);
        --inserisce i dati nella tabella libri
        INSERT INTO libri(isbn, sede) VALUES (_isbn, _sede);
        
        --se sono presenti autori
        IF _autori IS NOT NULL THEN
          --aggiungo i singoli autori per il libro inserito
          FOREACH _autore IN ARRAY _autori LOOP
             INSERT INTO scritto_da VALUES (_isbn, _autore);
          END LOOP;

        END IF; 
     END;
$$;

--aggiunge una nuova copia
CREATE OR REPLACE PROCEDURE add_copy(
    _isbn varchar(20),
    _sede uuid
)
LANGUAGE plpgsql
AS $$
     BEGIN
        SET search_path TO unibib;

        --inserisce i dati nella tabella libri
        INSERT INTO libri(isbn, sede) VALUES (_isbn, _sede);

     END;
$$;


--modifica un libro(e suoi eventuali autori)
CREATE OR REPLACE PROCEDURE edit_book(
    _isbn varchar(20),
    _titolo text,
    _trama text,
    _autori uuid[]
)
LANGUAGE plpgsql
AS $$
     DECLARE
       _autore uuid;
     BEGIN
        SET search_path TO unibib;
        
        --aggiorna i dati nella tabella cod_libro
        UPDATE cod_libro SET 
           titolo = _titolo,
           trama = _trama
        WHERE isbn = _isbn;   
        --se sono presenti autori
        IF _autori IS NOT NULL THEN
          --aggiungo i singoli autori per il libro inserito
          FOREACH _autore IN ARRAY _autori LOOP
             INSERT INTO scritto_da VALUES(_isbn, _autore);
          END LOOP;

        END IF; 
     END;
$$;

--modifica una copia(cioè la sua sede) dato il suo codice isbn, suo id e id della nuova sede
CREATE OR REPLACE PROCEDURE edit_copy(
    _isbn varchar(20),
    _id uuid,
    _sede uuid
)
LANGUAGE plpgsql
AS $$
     BEGIN
        SET search_path TO unibib;

        --aggiorna i dati nella tabella libri
        UPDATE libri SET 
           sede = _sede
        WHERE isbn = _isbn AND id = _id;
     END;
$$;

--elimina un libro e tutto le sue copie dato il suo codice isbn
CREATE OR REPLACE PROCEDURE delete_book(
    _isbn varchar(20)
)
LANGUAGE plpgsql
AS $$
     BEGIN
       SET search_path TO unibib;  

       DELETE FROM cod_libro
       WHERE isbn = _isbn;
     
     END;
$$;

--elimina una copia del libro dato il suo codice isbn e id
CREATE OR REPLACE PROCEDURE delete_copy(
   _isbn varchar(20),
   _id uuid
)
LANGUAGE plpgsql
AS $$
     BEGIN
       SET search_path TO unibib;

       DELETE FROM libri
       WHERE isbn = _isbn AND id = _id; 
     
     END;
$$;

--aggiunge un autore(con eventuali libri che ha scritto)
CREATE OR REPLACE PROCEDURE add_writer(
    _nome text,
    _cognome text,
    _d_nascita date, 
    _d_morte text,
    _bio text,
    _libri varchar(20)[]
)
LANGUAGE plpgsql
AS $$
     DECLARE 
        _id uuid;
        _libro varchar(20);
     BEGIN
       SET search_path TO unibib;

       -- inserisce i dati nella tabella autori
       INSERT INTO autori(nome, cognome, data_nascita) VALUES (_nome, _cognome, _d_nascita) RETURNING id INTO _id;
       -- se sono presenti altri dati
       IF _d_morte IS NOT NULL AND _d_morte <> '' THEN
         UPDATE autori SET
            data_morte = to_date(_d_morte,'YYYY-MM-DD')
         WHERE id = _id;
       END IF;
       IF _bio IS NOT NULL THEN
         UPDATE autori SET
            bio = _bio
         WHERE id = _id;
       END IF;
       -- se sono presenti libri
       IF _libri IS NOT NULL THEN
         -- aggiungo i singoli libri per l'autore selezionato
         FOREACH _libro IN ARRAY _libri LOOP
          INSERT INTO scritto_da VALUES (_libro, _id);
         END LOOP;
       END IF;
     END;
$$;

--modifica un autore dato il suo id
CREATE OR REPLACE PROCEDURE edit_writer(
    _id uuid,
    _nome text,
    _cognome text,
    _d_nascita date, 
    _d_morte text,
    _bio text,
    _libri varchar(20)[]
)
LANGUAGE plpgsql
AS $$
     DECLARE 
        _libro varchar(20);
     BEGIN
       SET search_path TO unibib;
       -- aggiorna i dati nella tabella autori
       UPDATE autori SET
         nome = _nome,
         cognome = _cognome,
         data_nascita = _d_nascita,
         data_morte = CASE WHEN (_d_morte IS NOT NULL AND _d_morte <> '') THEN to_date(_d_morte,'YYYY-MM-DD') END,
         bio = _bio
       WHERE id = _id;
       -- se sono presenti libri
       IF _libri IS NOT NULL THEN
         -- aggiungo i singoli libri per l'autore selezionato
         FOREACH _libro IN ARRAY _libri LOOP
          INSERT INTO scritto_da VALUES (_libro, _id);
         END LOOP;
       END IF;
     END;
$$;

--elimina un autore dato il suo id
CREATE OR REPLACE PROCEDURE delete_writer(
	_id uuid
)
LANGUAGE plpgsql
AS $$
     BEGIN
       SET search_path TO unibib;

       DELETE FROM autori 
       WHERE id = _id;
     END;
$$;

--aggiunge un nuovo prestito
CREATE OR REPLACE PROCEDURE add_rent(
    _isbn varchar(20),
    _id uuid,
    _c_f char(16),
    _d_inizio date,
    _d_fine date
)
LANGUAGE plpgsql
AS $$
     BEGIN
       SET search_path TO unibib;
       --inserisco i dati nella tabella prestito
       INSERT INTO prestiti(isbn, id, cod_fiscale, data_inizio, data_fine) VALUES (_isbn, _id, _c_f, _d_inizio, d_fine);
       
       --aggiorno il libro prestato aggiornando la disponibilità
       UPDATE libri SET 
         disponibilità = false
       WHERE isbn = _isbn AND id = _id;

       --aggiorno il numero di prestiti del lettore
       UPDATE autori SET 
         prestiti = prestiti + 1
       WHERE cod_fiscale = _c_f;

     END;
$$;

--modifico i dati di un prestito dato il suo id
CREATE OR REPLACE PROCEDURE edit_rent(
    _id uuid,
    _d_fine date,
    _d_consegna date
)
LANGUAGE plpgsql
AS $$
     DECLARE
      _c_f char(16);
      _data_fine date;
      _isbn varchar(20);
      _copia_id uuid;
     BEGIN
       SET search_path TO unibib;
       
       --aggiorno i dati nella tabella prestiti
       UPDATE prestiti SET
          data_fine = _d_fine,
          data_consegna = _d_consegna
       WHERE id = _id;
       
       --
       SELECT isbn, copia_id INTO _isbn, _copia_id
       FROM prestiti
       WHERE id = _id;

       --aggiorno lo stato del prestito confrontando la data di fine con quello della consegna
       SELECT data_fine, cod_fiscale INTO _data_fine, _c_f
       FROM prestiti
       WHERE id = _id;
       IF _d_consegna IS NOT NULL THEN
          IF Now() < _d_consegna THEN
              RAISE exception 'La data di consegna effettuata non può essere nel futuro';
          END IF;
          IF _d_consegna > _data_fine THEN
            UPDATE prestiti SET
                stato = 'In Ritardo'  
            WHERE id = _id;
            --e aggiorno il numero di ritardi e prestiti del lettore
            UPDATE lettori SET 
                ritardi = ritardi + 1,
                prestiti = prestiti -1
            WHERE cod_fiscale = _c_f;
          ELSE
            UPDATE prestiti SET
                stato = 'Consegnato'  
            WHERE id = _id;
            --e aggiorno il numero di prestiti del lettore
            UPDATE lettori SET 
                prestiti = prestiti -1
            WHERE cod_fiscale = _c_f;
          END IF;
          
          --aggiorno la disponibilità nella tabella libri
          UPDATE libri SET
               disponibilità = true
          WHERE isbn = _isbn AND id = _id;

       END IF;
     END;
$$;
