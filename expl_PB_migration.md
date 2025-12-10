**quand la migration bloque** (EX : ne trouve pas une table qui existe pourtant dans BDD)
il faut vérifier que >TOUTES les tables sont en InnoDB (sinon les FK ne fonctionnent pas)
> aller sur base de donnéé (ex: zest)
>SQL
> taper SHOW TABLE STATUS; + executer
> ca montre toutes les bases et on voit dans "engine" si c'est en InnoDB ou MyISAM (qui fait bloquer)
> ALTER TABLE nom_table ENGINE=InnoDB;
  ALTER TABLE nom_table ENGINE=InnoDB;
  ALTER TABLE nom_table ENGINE=InnoDB;
  ALTER TABLE nom_table ENGINE=InnoDB; (on fait toutes tables d'un coup)
> vérif avec SHOW TABLE STATUS

**IMPORTANT : faire aussi reset_password_request en InnoDB**