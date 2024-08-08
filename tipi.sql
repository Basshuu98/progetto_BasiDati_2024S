-- tipo di lettori disponibili nel database
CREATE TYPE TIPO_LETTORE AS enum ('Base', 'Premium');

-- tipo di stato del prestito
CREATE TYPE TIPO_STATO_PRESTITO AS enum ('In corso', 'Consegnato', 'In ritardo');