CREATE TABLE utenti(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 email text NOT NULL UNIQUE CHECK (email ~* '^[A-Za-z0-9._%-]+@(lettore|bibliotecario).it$'),
 password text NOT NULL CHECK (length(password) > 7),
 nome varchar(50) NOT NULL CHECK (nome ~* '^.+$'),
 cognome varchar(50) NOT NULL CHECK (cognome ~* '^.+$')
);

CREATE TABLE bibliotecari(
 id uuid PRIMARY KEY REFERENCES utenti(id)
);

CREATE TABLE lettori(
 cod_fiscale char(16) PRIMARY KEY CHECK(cod_fiscale ~ '^[A-Z]{6}[A-Z0-9]{2}[A-Z][A-Z0-9]{2}[A-Z][A-Z0-9]{3}[A-Z]$'
),
 id uuid NOT NULL UNIQUE REFERENCES utenti(id) ON UPDATE CASCADE,
 categoria TIPO_LETTORE NOT NULL,
 ritardi smallint NOT NULL DEFAULT 0
);

CREATE TABLE sedi(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 citta NOT NULL varchar(50),
 indirizzo NOT NULL varchar(100),
 UNIQUE (citta, indirizzo)
);

CREATE TABLE cod_libro(
 isbn varchar(20) PRIMARY KEY,
 titolo text NOT NULL,
 trama text NOT NULL
);

CREATE TABLE libri(
 isbn varchar(20) REFERENCES cod_libro(isbn) ON UPDATE CASCADE ON DELETE CASCADE,
 id uuid DEFAULT gen_random_uuid(),
 sede uuid NOT NULL REFERENCES sedi(id),
 disponibilita bool NOT NULL DEFAULT true,
 PRIMARY KEY(isbn, id)
);

CREATE TABLE autori(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 nome varchar(50) NOT NULL,
 cognome varchar(50) NOT NULL,
 data_nascita date NOT NULL,
 data_morte date,
 bio text
);

CREATE TABLE scritto_da(
 isbn varchar(20) NOT NULL REFERENCES cod_libro(isbn) ON UPDATE CASCADE ON DELETE CASCADE,
 id uuid NOT NULL REFERENCES autori(id) ON UPDATE CASCADE ON DELETE CASCADE,
 PRIMARY KEY (isbn, id)
);

CREATE TABLE prestiti(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 isbn varchar(20) NOT NULL,
 copia_id uuid NOT NULL,
 cod_fiscale char(16) NOT NULL REFERENCES lettori(cod_fiscale) ON UPDATE CASCADE ON DELETE CASCADE,
 data_inizio date NOT NULL,
 data_fine date NOT NULL,
 FOREIGN KEY(isbn, copia_id) REFERENCES libri(isbn, id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE VIEW ritardi_vista AS (
  SELECT s.id, s.citta, s.indirizzo, p.isbn, cl.titolo, u.nome, u.cognome FROM
     prestiti p 
  JOIN
     lettori le ON le.cod_fiscale = p.cod_fiscale
  JOIN
     utenti u ON u.id = le.id
  JOIN 
     libri li ON (li.isbn = p.isbn) AND (li.id = p.copia_id)
  JOIN 
     cod_libro cl ON cl.isbn = li.isbn
  JOIN 
     sedi s ON s.id = li.sede
  WHERE p.data_fine < CURRENT_DATE
);

CREATE OR REPLACE VIEW statistiche_vista AS (
  SELECT
     s.id,
     s.citta,
     s.indirizzo,
     COUNT(DISTINCT li.id) AS num_copie,
     COUNT(DISTINCT li.isbn) AS num_libri,
     COUNT(DISTINCT p.id) AS num_prestiti
  FROM sedi s
  JOIN libri li ON li.sede = s.id 
  LEFT JOIN prestiti p ON p.isbn = li.isbn AND p.copia_id = li.id
  GROUP BY s.id, s.citta, s.indirizzo
);