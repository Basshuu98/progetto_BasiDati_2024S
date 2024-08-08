--restituisce un lettore dato il suo codice fiscale
CREATE OR REPLACE FUNCTION get_reader(
     _c_f char(16)
)
RETURNS TABLE(
     _id uuid,
     _cod_fisc char(16),
     _nome text,
     _cognome text,
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
         SELECT l.id, l.cod_fiscale, u.nome, u.cognome, u.email, l.categoria, l.preestiti, l.ritardi
         FROM lettori AS l
         JOIN utenti AS u ON u.id = l.id
         WHERE l.cod_fiscale = _c_f;
     END;
$$;

--restituisce tutti i lettori
CREATE OR REPLACE FUNCTION get_all_readers()
RETURN TABLE(
     _id uuid,
     _cod_fisc char(16),
     _nome text,
     _cognome text,
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
         SELECT l.id, l.cod_fiscale, u.nome, u.cognome, u.email, l.categoria, l.preestiti, l.ritardi
         FROM lettori AS l
         JOIN utenti AS u ON u.id = l.id
     END;
$$;

--restituisce un libro dato il suo codice ISBN (più la sede)
CREATE OR REPLACE FUNCTION get_ISBN_book(
    _ISBN varchar(20),
    _sede uuid
)
RETURNS TABLE(
	__ISBN varchar(20),
	_copia uuid,
	_titolo text,
	_trama text, 
	_città varchar(50),
	_indirizzo varchar(100),
	_disponibilità bool
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT l.ISBN, l.id, l.titolo, l.trama, s.città, s.indirizzo, l.disponibilità
         FROM libri as l 
         JOIN sedi AS s ON s.id = l.sede
         WHERE l.ISBN = _ISBN AND ISNULL(_sede, l.sede);
     END;
$$;

--restituisce un libro dato il suo titolo (più la sede)
CREATE OR REPLACE FUNCTION get_title_book(
    _titolo text,
    _sede uuid
)
RETURNS TABLE(
     _ISBN varchar(20),
     _copia uuid,
     __titolo text,
     _trama text, 
     _città varchar(50),
     _indirizzo varchar(100),
     _disponibilità bool
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT l.ISBN, l.id, l.titolo, l.trama, s.città, s.indirizzo, l.disponibilità
         FROM libri as l 
         JOIN sedi AS s ON s.id = l.sede
         WHERE l.titolo = _titolo AND ISNULL(_sede, l.sede);
     END;
$$;

--restituisce tutti i libri
CREATE OR REPLACE FUNCTION get_all_books()
RETURNS TABLE(
     _ISBN varchar(20),
     _copia uuid,
     __titolo text,
     _trama text, 
     _città varchar(50),
     _indirizzo varchar(100),
     _disponibilità bool
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SELECT l.ISBN, l.id, l.titolo, l.trama, s.città, s.indirizzo, l.disponibilità
         FROM libri as l 
         JOIN sedi AS s ON s.id = l.sede
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
         FROM autori;
     END;
$$;

--restituisce gli autori di un libro dato il codice ISBN del libro
CREATE OR REPLACE FUNCTION get_writers_of_book(
    _ISBN varchar(20)
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
         WHERE sd.ISBN = _ISBN;
     END;
$$;

--restituisce i libri di un autore dato il suo id
CREATE OR REPLACE FUNCTION get_books_of_writer(
    _id uuid
)
RETURNS TABLE(
     _ISBN varchar(20),
     _titolo text
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT sd.ISBN, l.titolo
         FROM scritto_da AS sd 
         JOIN libri AS l ON l.ISBN = sd.ISBN
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
     _città varchar(50),
     _indirizzo varchar(100)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT *
         FROM sedi
         GROUP BY città;
     END;
$$;

--restituisce i prestiti in corso dato un lettore(il suo codice fiscale)
CREATE OR REPLACE FUNCTION get_active_rent_reader(
    _c_f char(16)
)
RETURNS TABLE(
   _ISBN varchar(20),
   _d_inizio date,
   _d_fine date
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT ISBN, data_inizio, data_fine 
         FROM prestiti 
         WHERE cod_fiscale = _c_f AND stato = 'In Corso'
     END;
$$;

--restituisce tutti i prestiti dato un lettore(il suo codice fiscale)
CREATE OR REPLACE FUNCTION get_all_rent_reader(
    _c_f char(16)
)
RETURNS TABLE(
   _ISBN varchar(20),
   _d_inizio date,
   _d_fine date,
   _stato TIPO_STATO_PRESTITO
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         SELECT ISBN, data_inizio, data_fine, stato 
         FROM prestiti
         WHERE cod_fiscale = _c_f; 
     END;
$$;

--restituisce le statische di una sede dato il suo id
CREATE OR REPLACE FUNCTION get_statistics(
   _id uuid
)
RETURNS TABLE(
     _città varchar(50),
     _indirizzo varchar(100),
     _n_copie integer,
     _n_libri integer,
     _n_prestiti integer
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
     _città varchar(50),
     _indirizzo varchar(100),
     _ISBN varchar(20),
     _titolo text,
     _nome varchar(20),
     _cognome varchar(50)
)
LANGUAGE plpgsql
AS $$
     BEGIN
         SET search_path TO unibib;

         RETURN QUERY
         SELECT città, indirizzo, ISBN, titolo, nome, cognome
         FROM ritardi_vista
         WHERE id = _id;
     END;
$$;

