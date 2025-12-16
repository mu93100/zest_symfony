**insérer des val dans table de la BDD direct sur phpMyAdmin**

ex : insérer dans entitée groupe (ds champs) les valeurs ('', '',   ETC);/ id auto
> SLQ (ds menu)
INSERT INTO groupe (nom, adresse_distrib, ville, is_referent, is_open, date_creation) VALUES
('BEFANA', 'plateau', 'Bagnolet', 0, 0, '2010-12-16 13:05:00'),
('37 et +', '37 rue de Vincennes', 'Montreuil', 0, 1, '2020-02-16 13:05:00'),
('ALEP', '7 rue Alexis Lepère', 'Montreuil', 0, 1, '2011-01-20 09:30:00');
