ALTER TABLE `contatos`.`contatos`
ADD COLUMN `id_usuario_criou` INT NULL AFTER `data_hora_ligou`,
ADD COLUMN `id_usuario_alterou` INT NULL AFTER `id_usuario_criou`;

ALTER TABLE `contatos`.`bairros`
ADD COLUMN `id_usuario_criou` INT NULL ,
ADD COLUMN `id_usuario_alterou` INT NULL AFTER `id_usuario_criou`;

ALTER TABLE `contatos`.`cidades`
ADD COLUMN `id_usuario_criou` INT NULL ,
ADD COLUMN `id_usuario_alterou` INT NULL AFTER `id_usuario_criou`;
