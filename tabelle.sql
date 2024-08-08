CREATE TABLE utenti(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 email text NOT NULL UNIQUE CHECK (email ~* '^[A-Za-z0-9._%-]+@biblio.it$'),
 password text NOT NULL CHECK (lenght(password) > 7),
 nome varchar(20) NOT NULL CHECK (nome ~* '^.+$'),
 cognome varchar(50) NOT NULL CHECK (cognome ~* '^.+$')
);

CREATE TABLE bibliotecari(
 id uuid PRIMARY KEY REFERENCES utenti(id)
);

CREATE TABLE lettori(
 cod_fiscale char(16) PRIMARY KEY CHECK(cod_fiscale ~ ^[A-Z]{6}[A-Z0-9]{2}[A-Z][A-Z0-9]{2}[A-Z][A-Z0-9]{3}[A-Z]$
),
 id uuid NOT NULL REFERENCES utenti(id) ON UPDATE CASCADE,
 categoria TIPO_LETTORE NOT NULL,
 prestiti smallint NOT NULL DEFAULT 0,
 ritardi smallint NOT NULL DEFAULT 0
);

CREATE TABLE sedi(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
 città varchar(50),
 indirizzo varchar(100),
 UNIQUE (città, indirizzo)
);

CREATE TABLE libri(
 ISBN varchar(20) NOT NULL,
 id uuid NOT NULL DEFAULT gen_random_uuid(),
 sede uuid REFERENCES sedi(id),
 titolo text NOT NULL,
 trama text NOT NULL,
 disponibilità bool NOT NULL DEFAULT true,
 PRIMARY KEY(ISBN, id)
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
 ISBN varchar(20) NOT NULL REFERENCES libri(ISBN) ON UPDATE CASCADE ON DELETE CASCADE,
 id uuid NOT NULL REFERENCES autori(id) ON UPDATE CASCADE ON DELETE CASCADE,
 PRIMARY KEY (ISBN, id)
);

CREATE TABLE prestiti(
 id uuid PRIMARY KEY DEFAULT gen_random_uuid,
 ISBN varchar(20) NOT NULL REFERENCES libri(ISBN) ON UPDATE CASCADE ON DELETE CASCADE,
 copia_id uuid NOT NULL REFERENCES libri(id) ON UPDATE CASCADE ON DELETE CASCADE,
 cod_fiscale NOT NULL REFERENCES lettori(cod_fiscale) ON UPDATE CASCADE ON DELETE CASCADE,
 data_inizio date NOT NULL,
 data_fine date NOT NULL,
 data_consegna date,
 stato TIPO_STATO_PRESTITO NOT NULL
);

CREATE VIEW ritardi_vista AS( 
  
  SELECT s.id, s.città, s.indirizzo, p.ISBN, li.titolo, le.nome, le.cognome FROM
     prestiti p 
  INNER JOIN
     lettori le ON le.cod_fiscale = p.cod_fiscale
  INNER JOIN 
     libri li ON (li.ISBN = p.ISBN) AND (li.id = p.copia_id)
  INNER JOIN 
     sedi s ON s.id = li.sede
  WHERE p.ritardo = 'In ritardo'  

  GROUP BY 
     s.città, s.indirizzo
);

CREATE VIEW statistiche_vista AS(
  SELECT
     s.id,
     s.città,
     s.indirizzo,
     (SELECT COUNT(*) FROM libri l WHERE l.sede = s.id) AS num_copie,
     (SELECT COUNT(DISTINCT ISBN) FROM libri l WHERE l.sede = s.id) AS num_libri,
     SELECT COUNT(p.id) AS num_prestiti
  FROM sedi s
  INNER JOIN 
     libri l ON l.sede = s.id 
  INNER JOIN
     prestiti p ON p.ISBN = l.ISBN 
  GROUP BY
     s.città, s.indirizzo       
);