
create database automotora;

CREATE USER 'devAutomotora'@'%' IDENTIFIED BY '3KV6Nn5gxRCpnk4';
ALTER USER 'devAutomotora'@'%' IDENTIFIED WITH mysql_native_password BY '3KV6Nn5gxRCpnk4';
FLUSH PRIVILEGES;

GRANT ALL PRIVILEGES ON automotora.* TO 'devAutomotora'@'%';
FLUSH PRIVILEGES;

create table usuarios(
    usuarioId int auto_increment,
    
    correo varchar(255),
    correoVal boolean not null default false,
    nombre varchar(255),
    apellido varchar(255),
    tipoUsuario varchar(50) not null,
    contrasena varchar(255) not null,
    existe boolean not null default true,
    primary key (usuarioId, correo)
);

create table clientes(
    clienteId int primary key,
    foreign key (clienteId) references usuarios(usuarioId)
);

create table permisos(
    permisoId int primary key auto_increment,
    sigla varchar(20) not null,
    nombre varchar(255) not null,
    descripcion text
);

create table administrativos(
    administrativoId int primary key,
    descripcion text,
    telefono varchar(15),
    permiso int not null,
    foreign key (administrativoId) references usuarios(usuarioId),
    foreign key (permiso) references permisos(permisoId)
);


-- Tablas para la relación de jefes y administrativos
-- falta aniadir la tabla de jefes
create table jefe(
    jefeId int primary key,
    administrativoId int not null,
    foreign key (jefeId) references usuarios(usuarioId),
    foreign key (administrativoId) references administrativos(administrativoId)
);

CREATE VIEW vista_administrativos_usuarios_permisos AS
SELECT 
    u.usuarioId,
    u.correo,
    u.nombre,
    u.apellido,
    a.descripcion AS descripcion_administrativo,
    a.telefono,
    p.sigla AS permiso_sigla
FROM usuarios u JOIN administrativos a ON u.usuarioId = a.administrativoId
JOIN permisos p ON a.permiso = p.permisoId;

CREATE VIEW vista_clientes AS
SELECT 
    u.usuarioId,
    u.correo,
    u.nombre,
    u.apellido
FROM usuarios u
JOIN clientes c ON u.usuarioId = c.clienteId;



DELIMITER $$

CREATE PROCEDURE registrarCliente(
    IN correoCliente varchar(255),
    IN nombreCliente varchar(255),
    IN apellidoCliente varchar(255),
    IN contrasenaCliente varchar(255)
)
BEGIN
    -- Declara el manejador de excepciones antes de iniciar la transacción
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Si ocurre un error, revierte todas las operaciones de la transacción
        ROLLBACK;
    END;
    
    -- Inicia una transacción
    START TRANSACTION;
    
    -- Intenta insertar en la tabla usuarios
    INSERT INTO usuarios(correo, nombre, apellido, tipoUsuario, contrasena)
    VALUES(correoCliente, nombreCliente, apellidoCliente, 'cliente', contrasenaCliente);
    
    -- Intenta insertar en la tabla clientes
    INSERT INTO clientes(clienteId) VALUES(LAST_INSERT_ID());
    
    -- Si ambas inserciones son exitosas, confirma la transacción
    COMMIT;
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER correoValAFTERUPDATE
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.correo <> OLD.correo THEN
        UPDATE usuarios SET correoval = 0 WHERE usuarioId = NEW.usuarioId;
    END IF;
END$$

DELIMITER ;