/**** tables ****/
DROP TABLE IF EXISTS wcf1_ultimate_blocktype;
CREATE TABLE wcf1_ultimate_blocktype (
    blockTypeID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    packageID INT(10) NOT NULL,
    blockTypeName VARCHAR(255) NOT NULL DEFAULT '',
    blockTypeClassName VARCHAR(255) NOT NULL DEFAULT '',
    KEY (packageID),
    UNIQUE KEY packageID (packageID, blockTypeName)
);

DROP TABLE IF EXISTS wcf1_ultimate_block;
CREATE TABLE wcf1_ultimate_block (
    blockID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    blockTypeID INT(10) NOT NULL,
    query VARCHAR(255) NOT NULL DEFAULT '',
    parameters VARCHAR(255) NOT NULL DEFAULT '',
    KEY (blockTypeID)
);

DROP TABLE IF EXISTS wcf1_ultimate_block_to_template;
CREATE TABLE wcf1_ultimate_block_to_template (
    blockID INT(10) NOT NULL,
    templateID INT(10) NOT NULL,
    KEY (blockID),
    KEY (templateID)
);

DROP TABLE IF EXISTS wcf1_ultimate_template;
CREATE TABLE wcf1_ultimate_template (
    templateID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    packageID INT(10) NOT NULL,
    templateName VARCHAR(255) NOT NULL DEFAULT '',
    templateBlocks VARCHAR(255) NOT NULL DEFAULT '',
    KEY (packageID)
);

ALTER TABLE wcf1_ultimate_block ADD FOREIGN KEY (blockTypeID) REFERENCES wcf1_ultimate_blocktype (blockTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_ultimate_block_to_template ADD FOREIGN KEY (blockID) REFERENCES wcf1_ultimate_block (blockID) ON DELETE CASCADE;
ALTER TABLE wcf1_ultimate_block_to_template ADD FOREIGN KEY (templateID) REFERENCES wcf1_ultimate_template (templateID) ON DELETE CASCADE;
ALTER TABLE wcf1_ultimate_blocktype ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
ALTER TABLE wcf1_ultimate_template ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;