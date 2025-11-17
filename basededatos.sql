create database if not exists bd_344tienda;

use bd_344tienda;

create table usuarios(
id int auto_increment primary key,
nombre varchar(100) not null,
email varchar(100) unique not null,
password varchar(100) not null,
rol enum('admin','empleado','cliente')not null default 'cliente'
);

create table productos(
id int auto_increment primary key,
nombre varchar(100) not null,
descripcion text,
precio decimal(10,2) not null,
stock int not null default 0,
imagen varchar(255)
);

create table ventas(
id int auto_increment primary key,
usuario_id int not null,
total decimal(12,2) not null default 0.00,
creado_en timestamp default current_timestamp,
foreign key (usuario_id) references usuarios(id)
);

create table venta_detalle(
id int auto_increment primary key,
venta_id int not null,
producto_id int not null,
cantidad int not null default 1,
precio_unitario decimal(10,2) not null,
subtotal decimal(10,2) not null,
foreign key (venta_id) references ventas(id),
foreign key (producto_id) references productos(id)
);

commit;

INSERT INTO `bd_344tienda`.`usuarios` (`nombre`, `email`, `password`, `rol`) VALUES ('nelton', 'nelton@gmail.com', '12345', 'admin');