/**** tables ****/

DROP TABLE IF EXISTS wcf1_component;
CREATE TABLE wcf1_component (
    componentID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    packageID INT(10) NOT NULL,
    className VARCHAR(255) NOT NULL DEFAULT '',
    title VARCHAR(255) NOT NULL DEFAULT '',
    UNIQUE KEY packageID(packageID, className)   
);

ALTER TABLE wcf1_component ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;