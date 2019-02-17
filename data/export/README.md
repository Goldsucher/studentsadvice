##Basis-Tabellen // CSV-File:
Das sind die Tabellen, die Daten aus den CSV-Dateien von der BHT haben und uns zur Verfügung gestellt wurden.


Abschluss // pseudoabschluss.csv

hzb // pseudohzb.csv

noten // pseudonoten.csv

units // units.csv

##Extension-Tabellen:
Das sind Tabellen, die Erweiterungen der Basis-Tabellen beinhalten, d.h. diese behinhalten zusätzlich der Student_id oder der 
Unit_id zusätzliche Spalten mit Extra-Informationen.

####hzb_extension:
Folgende Spalten sind enthalten:

Student_id:
entspricht der der Student_id aus der hzb-Tabelle

Wechsel: 1 = Wechselstudent // 0 = kein Wechselstudent

Abbruch: 1 = Abbrecher // 0 = kein Abbrecher 

Durchschnittsnote: Durchschnittsnote des Studenten von allen Kursen mit BNF 5 ohne die Note 5 

EndNote: Endgültige Abschlussnote des Studenten mit Abschluss 

=>
FachEndNote (abschluss-TAbelle) + Durchschnittsnote (hzb_extension-Tabelle) / 2

####units_extension:
Folgende Spalten sind enthalten:

Unit_id: entspricht der Unit_id aus der units-Tabelle

Wahlplficht: 1 = Wahlplfichtkurs // 0 = kein Wahlpflichtkurs

Plansemester: Welche Kurs ist für welches Semester geplant. 0 = keine Zuordnung, da nicht ersichtlich aus der Äquivalenzliste

Durschnittsnote: Durchschnittsnote von jedem Kurs von allen Studenten, die den Kurs beendet haben.

Durschnittsnote_5: Durchschnittsnote des Kurses von allen Studenten, die den Kurs beendet haben. 
Mit der Ausnahme, dass die Benotung 5 mit eingerechnet ist

###zusätzliche Tabellen:
units_equivalence: entspricht der der Äquivalenz-Liste die aus den untschiedlichen Studienordnungen angelegt wurde.
units_original: entspricht der originalen units.csv der BHT (wurde aber nicht weiterverwendet)

