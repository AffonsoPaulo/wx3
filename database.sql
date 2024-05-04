CREATE
DATABASE IF NOT EXISTS acme DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

use
acme;

CREATE TABLE category
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    description TEXT         NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE products
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(128)  NOT NULL,
    color       VARCHAR(100)  NOT NULL,
    image       VARCHAR(256),
    price       DECIMAL(8, 2) NOT NULL,
    discount    DECIMAL(8, 2) NOT NULL,
    description TEXT,
    weight      DECIMAL(8, 2) NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE variations
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    size       VARCHAR(16),
    quantity   INT NOT NULL,
    product_id INT,
    FOREIGN KEY (product_id) REFERENCES products (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE client
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(128) NOT NULL,
    cpf       VARCHAR(14)  NOT NULL,
    birthDate DATE         NOT NULL,
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE address
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    street       VARCHAR(128) NOT NULL,
    city         VARCHAR(128) NOT NULL,
    neighborhood VARCHAR(128) NOT NULL,
    state        VARCHAR(2)   NOT NULL,
    number       INT          NOT NULL,
    zipCode      VARCHAR(9)   NOT NULL,
    client_id    INT,
    FOREIGN KEY (client_id) REFERENCES client (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE sale
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    client_id     INT,
    address_id    INT,
    totalPrice    DECIMAL(8, 2) NOT NULL,
    shippingPrice DECIMAL(8, 2) NOT NULL,
    discountPrice DECIMAL(8, 2) NOT NULL,
    paymentMethod VARCHAR(100)  NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client (id),
    FOREIGN KEY (address_id) REFERENCES address (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE sale_products
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    sale_id       INT,
    variations_id INT,
    quantity      INT           NOT NULL,
    price         DECIMAL(8, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sale (id),
    FOREIGN KEY (variations_id) REFERENCES variations (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;



