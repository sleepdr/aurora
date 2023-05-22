create table if not exists items (
    penguin_id int not null,
    item_id int not null
);

create table if not exists penguins (
    id serial,
    username text not null,
    hash text not null,
    salt text not null,
    login_key text not null,
    rank int not null default 0,
    banned int not null default 0,
    coins int not null default 10000,
    head int not null default 0,
    face int not null default 0,
    neck int not null default 0,
    body int not null default 0,
    hands int not null default 0,
    feet int not null default 0,
    colour int not null default 0,
    photo int not null default 0,
    flag int not null default 0
);