CREATE TABLE IF NOT EXISTS intentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario)
);