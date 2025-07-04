-- This is used for old databases who doesn't have auto increment in their id
--
-- Change studentTb to the table you want to add autoincrement
CREATE SEQUECE "studentTb_sequence";

ALTER TABLE "studentTb"
ALTER COLUMN id
SET DEFAULT nextval ("studentTb_sequence");

ALTER SEQUENCE "studentTb_sequence" OWNED BY "studentTb".id;