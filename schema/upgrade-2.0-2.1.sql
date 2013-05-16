ALTER TABLE mt_vocab  MODIFY huri_code varchar(14) NULL ;
update data_dict set validation='number' where datatype='N';