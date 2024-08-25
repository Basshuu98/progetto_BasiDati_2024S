-- verifica il login di un utente data email e password
-- restituisce id in caso di login corretto
CREATE OR REPLACE FUNCTION login (
  _email TEXT,
  _password TEXT
)
  RETURNS TABLE (
    _id uuid
  )
  LANGUAGE plpgsql
  AS $$
    BEGIN

      SET search_path TO unibib;

      RETURN QUERY
        SELECT u.id
        FROM utenti AS u
        WHERE email = _email
        AND password is NOT NULL 
        AND password = crypt(_password, password);

    END;
  $$; 

--restituisce un lettore dato il suo codice fiscale
CREATE OR REPLACE FUNCTION get_reader(
     __id uuid
)
RETURNS TABLE(
     _id uuid,
     _cod_fisc char(16),
     _nome varchar(20),
     _cognome varchar(50),
     _email text,
     _categoria TIPO_LETTORE,
     _prestiti smallint,
     _ritardi smallint
)
LANGUAGE plpgsql
AS $$ 
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT l.id, l.cod_fiscale, u.nome, u.cognome, u.email, l.categoria, l.prestiti, l.ritardi
         FROM lettori AS l
         JOIN utenti AS u ON u.id = l.id
         WHERE l.id = __id;
     END;
$$;

--restituisce tutti i lettori
CREATE OR REPLACE FUNCTION get_all_readers()
RETURNS TABLE(
     _id uuid,
     _cod_fisc char(16),
     _nome varchar(20),
     _cognome varchar(50),
     _email text,
     _categoria TIPO_LETTORE,
     _prestiti smallint,
     _ritardi smallint
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT l.id, l.cod_fiscale, u.nome, u.cognome, u.email, l.categoria, l.prestiti, l.ritardi
         FROM lettori AS l
         JOIN utenti AS u ON u.id = l.id;
     END;
$$;

--restituisce un admin dato il suo id
CREATE OR REPLACE FUNCTION get_librarian(
    _id uuid
)
RETURNS TABLE(
     __id uuid,
     _nome varchar(20),
     _cognome varchar(50),
     _email text
)
LANGUAGE plpgsql
AS $$ 
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT u.id, u.nome, u.cognome, u.email
         FROM bibliotecari AS b
         JOIN utenti AS u ON u.id = b.id
         WHERE u.id = _id;
     END;
$$;


--restituisce un libro dato il suo codice isbn (più la sede)
CREATE OR REPLACE FUNCTION get_isbn_book(
    _isbn varchar(20),
    _sede uuid
)
RETURNS TABLE(
	__isbn varchar(20),
	_copia uuid,
	_titolo text,
	_trama text, 
	_citta varchar(50),
	_indirizzo varchar(100),
	_disponibilita bool
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT l.isbn, l.id, cl.titolo, cl.trama, s.città, s.indirizzo, l.disponibilità
         FROM libri AS l
         JOIN cod_libro AS cl ON cl.isbn = l.isbn 
         JOIN sedi AS s ON s.id = l.sede
         WHERE l.isbn = _isbn AND (l.sede = _sede OR _sede IS NULL);
     END;
$$;

--restituisce un libro dato il suo titolo (più la sede)
CREATE OR REPLACE FUNCTION get_title_book(
    _titolo text,
    _sede uuid
)
RETURNS TABLE(
     _isbn varchar(20),
     _copia uuid,
     __titolo text,
     _trama text, 
     _citta varchar(50),
     _indirizzo varchar(100),
     _disponibilita bool
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT cl.isbn, l.id, cl.titolo, cl.trama, s.città, s.indirizzo, l.disponibilità
         FROM cod_libro AS cl 
         JOIN libri AS l ON l.isbn = cl.isbn
         JOIN sedi AS s ON s.id = l.sede
         WHERE cl.titolo = _titolo AND (l.sede = _sede OR _sede IS NULL);
     END;
$$;

--restituisce tutti i libri
CREATE OR REPLACE FUNCTION get_all_books()
RETURNS TABLE(
     _isbn varchar(20),
     _copia uuid,
     __titolo text,
     _trama text, 
     _sede uuid,
     _citta varchar(50),
     _indirizzo varchar(100),
     _disponibilita bool,
     _autori text
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT cl.isbn, l.id, cl.titolo, cl.trama, s.id, s.città, s.indirizzo, l.disponibilità, string_agg(CONCAT(a.nome, ' ',a.cognome), ', ')
         FROM cod_libro AS cl
         JOIN libri AS l ON l.isbn = cl.isbn
         JOIN sedi AS s ON s.id = l.sede
         LEFT JOIN scritto_da AS sd ON sd.isbn = cl.isbn
         LEFT JOIN autori AS a ON a.id = sd.id
         
         GROUP BY cl.isbn, l.id, cl.titolo, cl.trama, s.id, s.città, s.indirizzo, l.disponibilità
         ORDER BY cl.isbn;
     END;
$$;

--restituisce un autore dato il suo id
CREATE OR REPLACE FUNCTION get_writer(
   _id uuid
)
RETURNS TABLE(
     _nome varchar(50),
     _cognome varchar(50),
     _d_nascita date,
     _d_morte date,
     _bio text
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT nome, cognome, data_nascita, data_morte, bio 
         FROM autori
         WHERE id = _id;
     END;
$$;

--restituisce tutti gli autori
CREATE OR REPLACE FUNCTION get_all_writers()
RETURNS TABLE(
     _id uuid,
     _nome varchar(50),
     _cognome varchar(50),
     _d_nascita date,
     _d_morte date,
     _bio text
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT id, nome, cognome, data_nascita, data_morte, bio 
         FROM autori;
     END;
$$;

--restituisce gli autori di un libro dato il codice isbn del libro
CREATE OR REPLACE FUNCTION get_writers_of_book(
    _isbn varchar(20)
)
RETURNS TABLE(
     _id uuid,
     _nome varchar(50),
     _cognome varchar(50)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT sd.id, a.nome, a.cognome
         FROM scritto_da AS sd 
         JOIN autori AS a ON a.id = sd.id
         WHERE sd.isbn = _isbn;
     END;
$$;

--restituisce i libri di un autore dato il suo id
CREATE OR REPLACE FUNCTION get_books_of_writer(
    _id uuid
)
RETURNS TABLE(
     _isbn varchar(20),
     _titolo text
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT sd.isbn, cl.titolo
         FROM scritto_da AS sd 
         JOIN cod_libro AS cl ON cl.isbn = sd.isbn
         WHERE sd.id = _id;
     END;
$$;

--restituisce una sede data la città
CREATE OR REPLACE FUNCTION get_city_library(
    _città varchar(50)
)
RETURNS TABLE(
     _id uuid,
     _indirizzo varchar(100)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT id, indirizzo 
         FROM sedi
         WHERE città = _città;
     END;
$$;

--restituisce tutte le sedi
CREATE OR REPLACE FUNCTION get_all_libraries()
RETURNS TABLE(
     _id uuid,
     _citta varchar(50),
     _indirizzo varchar(100)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT *
         FROM sedi
         ORDER BY città;
     END;
$$;

--restituisce tutti i prestiti in corso 
CREATE OR REPLACE FUNCTION get_active_rent()
RETURNS TABLE(
    _id uuid,
    _isbn varchar(20),
    _copia uuid,
    _cod_fisc char(16),
    _citta varchar(50),
    _indirizzo varchar(100),
    _d_inizio date,
    _d_fine date
)
LANGUAGE plpgsql
AS $$
     BEGIN
        SET search_path TO unibib;

        RETURN QUERY
        SELECT p.id, p.isbn, p.copia_id, p.cod_fiscale, s.città, s.indirizzo, p.data_inizio, p.data_fine
        FROM prestiti AS p
        JOIN libri AS l ON l.isbn = p.isbn AND l.id = p.copia_id
        JOIN sedi AS s ON s.id = l.sede
        WHERE p.stato = 'In corso' 
        
        ORDER BY s.città, s.indirizzo;
     END;
$$;

--restituisce i prestiti in corso dato un lettore(il suo codice fiscale)
CREATE OR REPLACE FUNCTION get_active_rent_reader(
    _c_f char(16)
)
RETURNS TABLE(
   _isbn varchar(20),
   _d_inizio date,
   _d_fine date
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT isbn, data_inizio, data_fine 
         FROM prestiti 
         WHERE cod_fiscale = _c_f AND stato = 'In Corso';
     END;
$$;

--restituisce tutti i prestiti dato un lettore(il suo codice fiscale)
CREATE OR REPLACE FUNCTION get_all_rent_reader(
    _c_f char(16)
)
RETURNS TABLE(
   _isbn varchar(20),
   _d_inizio date,
   _d_fine date,
   _stato TIPO_STATO_PRESTITO
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         SELECT isbn, data_inizio, data_fine, stato 
         FROM prestiti
         WHERE cod_fiscale = _c_f; 
     END;
$$;

--restituisce le statische di una sede dato il suo id
CREATE OR REPLACE FUNCTION get_statistics(
   _id uuid
)
RETURNS TABLE(
     _citta varchar(50),
     _indirizzo varchar(100),
     _n_copie bigint,
     _n_libri bigint,
     _n_prestiti bigint
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT città, indirizzo, num_copie, num_libri, num_prestiti
         FROM statistiche_vista
         WHERE id = _id;
     END;
$$;

--restituisce i report di una sede dato il suo id
CREATE OR REPLACE FUNCTION get_report(
  _id uuid
)
RETURNS TABLE(
     _citta varchar(50),
     _indirizzo varchar(100),
     _isbn varchar(20),
     _titolo text,
     _nome varchar(20),
     _cognome varchar(50)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT città, indirizzo, isbn, titolo, nome, cognome
         FROM ritardi_vista
         WHERE id = _id;
     END;
$$;

