CREATE TABLE campanha (
	id SERIAL PRIMARY KEY,
	descricao VARCHAR (255)
);
	
CREATE TABLE jogo (
	id SERIAL PRIMARY KEY,
	time1 VARCHAR (255),
	time2 VARCHAR (255),
	campanha INTEGER NOT NULL
);

CREATE TABLE placar (
	jogo_id INTEGER PRIMARY KEY,
	placar_time1 INTEGER,
	placar_time2 INTEGER,
	id_jogador INTEGER
);

CREATE TABLE jogador (
	id SERIAL PRIMARY KEY,
	nome VARCHAR (255)
);