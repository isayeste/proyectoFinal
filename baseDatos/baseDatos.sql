CREATE DATABASE IF NOT EXISTS psyconnect;
use psyconnect;

-- PACIENTES
CREATE TABLE pacientes(
    emailPaciente VARCHAR(100) PRIMARY KEY NOT NULL,
    contrasenia VARCHAR (100),
    nombre VARCHAR(100),
    fechaNacimiento DATE,
    fotoPerfil BLOB
);

-- HORARIOS
CREATE TABLE horarios(
    idHorario INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    fechaInicio DATETIME,
    fechaFin DATETIME,  
    estado ENUM('libre', 'ocupado', 'noLaboral')
);

-- CITAS
CREATE TABLE citas(
    idCita INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    motivo TEXT,
    via ENUM('online', 'presencial'),
    emailPaciente VARCHAR(100),
    idHorario INT,
    FOREIGN KEY (emailPaciente) REFERENCES pacientes(emailPaciente),
    FOREIGN KEY (idHorario) REFERENCES horarios(idHorario)
);

