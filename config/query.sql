CREATE DATABASE proyecto_mvc;

USE proyecto_mvc;


CREATE TABLE tb_torres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_torre VARCHAR(50) NOT NULL
);

INSERT INTO tb_torres (nombre_torre) VALUES 
('Torre 1'),
('Torre 2'),
('Torre 3'),
('Torre 4'),
('Torre 5');

CREATE TABLE tb_apartamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_apartamento VARCHAR(10) NOT NULL,
    piso INT NOT NULL
);

INSERT INTO tb_apartamentos (numero_apartamento, piso) VALUES
('101', 1), ('102', 1), ('103', 1), ('104', 1), ('105', 1),
('201', 2), ('202', 2), ('203', 2), ('204', 2), ('205', 2),
('301', 3), ('302', 3), ('303', 3), ('304', 3), ('305', 3),
('401', 4), ('402', 4), ('403', 4), ('404', 4), ('405', 4),
('501', 5), ('502', 5), ('503', 5), ('504', 5), ('505', 5),
('601', 6), ('602', 6), ('603', 6), ('604', 6), ('605', 6),
('701', 7), ('702', 7), ('703', 7), ('704', 7), ('705', 7),
('801', 8), ('802', 8), ('803', 8), ('804', 8), ('805', 8),
('901', 9), ('902', 9), ('903', 9), ('904', 9), ('905', 9);


CREATE TABLE tb_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    numero_celular VARCHAR(20) NOT NULL,
    id_torre INT NULL,
    id_apartamento INT NULL,
    rol ENUM('administrador', 'porteria', 'residente') NOT NULL DEFAULT 'residente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_torre FOREIGN KEY (id_torre) REFERENCES tb_torres(id),
    CONSTRAINT fk_apartamento FOREIGN KEY (id_apartamento) REFERENCES tb_apartamentos(id),
    CONSTRAINT unique_torre_apartamento UNIQUE (id_torre, id_apartamento)
);


CREATE TABLE tb_solicitudes_parqueadero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_persona VARCHAR(100) NOT NULL,
    documento_persona VARCHAR(20) NOT NULL,
    tipo_vehiculo ENUM('moto', 'carro') NOT NULL,
    placa_vehiculo VARCHAR(20) NOT NULL,
    tipo_parqueadero ENUM('residente', 'visitante') NOT NULL,
    pago ENUM('si', 'no') NOT NULL,
    fecha_hora_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_hora_liberacion DATETIME DEFAULT NULL,
    estado ENUM('ocupado', 'libre') DEFAULT 'ocupado'
);


CREATE TABLE tb_parqueaderos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_parqueadero VARCHAR(10) NOT NULL,
    nombre_parqueadero VARCHAR(100) NOT NULL,
    estado VARCHAR(50) DEFAULT NULL
);


INSERT INTO tb_parqueaderos (id, numero_parqueadero, nombre_parqueadero) VALUES 
(1, '001', 'Parqueadero 1'),
(2, '002', 'Parqueadero 2'),
(3, '003', 'Parqueadero 3'),
(4, '004', 'Parqueadero 4'),
(5, '005', 'Parqueadero 5'),
(6, '006', 'Parqueadero 6'),
(7, '007', 'Parqueadero 7'),
(8, '008', 'Parqueadero 8'),
(9, '009', 'Parqueadero 9'),
(10, '010', 'Parqueadero 10'),
(11, '011', 'Parqueadero 11'),
(12, '012', 'Parqueadero 12'),
(13, '013', 'Parqueadero 13'),
(14, '014', 'Parqueadero 14'),
(15, '015', 'Parqueadero 15'),
(16, '016', 'Parqueadero 16'),
(17, '017', 'Parqueadero 17'),
(18, '018', 'Parqueadero 18'),
(19, '019', 'Parqueadero 19'),
(20, '020', 'Parqueadero 20'),
(21, '021', 'Parqueadero 21'),
(22, '022', 'Parqueadero 22'),
(23, '023', 'Parqueadero 23'),
(24, '024', 'Parqueadero 24'),
(25, '025', 'Parqueadero 25'),
(26, '026', 'Parqueadero 26'),
(27, '027', 'Parqueadero 27'),
(28, '028', 'Parqueadero 28'),
(29, '029', 'Parqueadero 29'),
(30, '030', 'Parqueadero 30'),
(31, '031', 'Parqueadero 31'),
(32, '032', 'Parqueadero 32'),
(33, '033', 'Parqueadero 33'),
(34, '034', 'Parqueadero 34'),
(35, '035', 'Parqueadero 35'),
(36, '036', 'Parqueadero 36'),
(37, '037', 'Parqueadero 37'),
(38, '038', 'Parqueadero 38'),
(39, '039', 'Parqueadero 39'),
(40, '040', 'Parqueadero 40'),
(41, '041', 'Parqueadero 41'),
(42, '042', 'Parqueadero 42'),
(43, '043', 'Parqueadero 43'),
(44, '044', 'Parqueadero 44'),
(45, '045', 'Parqueadero 45'),
(46, '046', 'Parqueadero 46'),
(47, '047', 'Parqueadero 47'),
(48, '048', 'Parqueadero 48'),
(49, '049', 'Parqueadero 49'),
(50, '050', 'Parqueadero 50'),
(51, '051', 'Parqueadero 51'),
(52, '052', 'Parqueadero 52'),
(53, '053', 'Parqueadero 53'),
(54, '054', 'Parqueadero 54'),
(55, '055', 'Parqueadero 55'),
(56, '056', 'Parqueadero 56'),
(57, '057', 'Parqueadero 57'),
(58, '058', 'Parqueadero 58'),
(59, '059', 'Parqueadero 59'),
(60, '060', 'Parqueadero 60'),
(61, '061', 'Parqueadero 61'),
(62, '062', 'Parqueadero 62'),
(63, '063', 'Parqueadero 63'),
(64, '064', 'Parqueadero 64'),
(65, '065', 'Parqueadero 65'),
(66, '066', 'Parqueadero 66'),
(67, '067', 'Parqueadero 67'),
(68, '068', 'Parqueadero 68'),
(69, '069', 'Parqueadero 69'),
(70, '070', 'Parqueadero 70'),
(71, '071', 'Parqueadero 71'),
(72, '072', 'Parqueadero 72'),
(73, '073', 'Parqueadero 73'),
(74, '074', 'Parqueadero 74'),
(75, '075', 'Parqueadero 75'),
(76, '076', 'Parqueadero 76'),
(77, '077', 'Parqueadero 77'),
(78, '078', 'Parqueadero 78'),
(79, '079', 'Parqueadero 79'),
(80, '080', 'Parqueadero 80'),
(81, '081', 'Parqueadero 81'),
(82, '082', 'Parqueadero 82'),
(83, '083', 'Parqueadero 83'),
(84, '084', 'Parqueadero 84'),
(85, '085', 'Parqueadero 85'),
(86, '086', 'Parqueadero 86'),
(87, '087', 'Parqueadero 87'),
(88, '088', 'Parqueadero 88'),
(89, '089', 'Parqueadero 89'),
(90, '090', 'Parqueadero 90'),
(91, '091', 'Parqueadero 91'),
(92, '092', 'Parqueadero 92'),
(93, '093', 'Parqueadero 93'),
(94, '094', 'Parqueadero 94'),
(95, '095', 'Parqueadero 95'),
(96, '096', 'Parqueadero 96'),
(97, '097', 'Parqueadero 97'),
(98, '098', 'Parqueadero 98'),
(99, '099', 'Parqueadero 99'),
(100, '100', 'Parqueadero 100');



-- Tabla de historial de parqueaderos
CREATE TABLE tb_historial_parqueaderos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parqueadero_id INT NOT NULL,
    nombre_persona VARCHAR(100) NOT NULL,
    documento_persona VARCHAR(20) NOT NULL,
    tipo_vehiculo ENUM('carro', 'moto') NOT NULL,
    placa_vehiculo VARCHAR(10) NOT NULL,
    tipo_parqueadero ENUM('residente', 'visitante') NOT NULL,
    pago BOOLEAN DEFAULT 0,
    fecha_solicitud DATETIME NOT NULL,
    fecha_liberacion DATETIME DEFAULT NULL,
    valor_pagado DECIMAL(10, 2) DEFAULT NULL,
    estado ENUM('pendiente_aprobacion', 'ocupado', 'rechazado', 'libre') NOT NULL DEFAULT 'pendiente_aprobacion',
    FOREIGN KEY (parqueadero_id) REFERENCES tb_parqueaderos(id) ON DELETE CASCADE
);


CREATE TABLE tb_zonas_comunes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_zona VARCHAR(50) NOT NULL,
    solicitante VARCHAR(100) NOT NULL,
    torre VARCHAR(10) NOT NULL,
    apartamento VARCHAR(10) NOT NULL,
    fecha_solicitud DATE NOT NULL,
    fecha_solicitada DATE NOT NULL,
    estado ENUM('ocupado', 'pendiente_aprobacion', 'libre') DEFAULT 'pendiente_aprobacion'
);



-- Insertar un usuario administrador en la tabla tb_usuarios
INSERT INTO tb_usuarios (correo, clave, nombre_completo, numero_documento, numero_celular, rol, id_torre, id_apartamento)
VALUES (
    'camilo.ovalle@outlook.es', 
    '$2y$10$s3n8fe4ePDCv03NFgUkRSuJnyGPFj3ylLahLu8IM82T0ZLIaV2fRm',
    'Juan Camilo Ovalle Cardenas', 
    '1023959791', 
    '3508596818', 
    'administrador', 
    1,
    2 
);


SELECT * FROM tb_parqueaderos;
SELECT * FROM tb_zonas_comunes;
SELECT * FROM tb_usuarios;


