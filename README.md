## Kurze Erklärung der Funktionalität

####index.php: Einstiegsdatei für die Smarty-Templates
- index.php?timeline=<student_id>
     - Anzeige des Werdegangs eines Studenten

- index.php?line_chart=<student_id>
    - Anzeige für das Verhältnis von Belegung und Bestanden pro Semester eines Studenten
    
- index.php?avg_grades
    - Anzeige alle Kurse und deren Durchschnittsnoten. Je ein Diagramm für Kurse pro Plansemester

####api.php: Schnittstelle für Dashboard und Datenbank
- api.php?diagram=timeline_student&student=<student_id>&apikey=<api_key>
    - Liefert die Daten für die Timeline eines Studenten
    
- api.php?diagram=duration_of_study_dropouts&apikey=<api_key>
    - Wie lange waren Studienabbrecher immatrikuliert?
    
- api.php?diagram=duration_of_graduation&apikey=<api_key>
    - Wie lange haben Absolvent gebraucht?
    
- api.php?diagram=grade_distribution_per_semester&apikey=<api_key>
    - Liefert die Notenverteilung pro Semester

- api.php?diagram=number_of_dropouts_per_semester&apikey=<api_key>
    - Anzahl der Abbrecher pro Semester  
    
APIKEY kann in der Config.php angepasst werden   

#####import.php: Import die Daten der CSV-Dateien in data/final_import/. Pfad kann in der Config.php angepasst werden.
- extra Config.php: src/php/import/ImportConfig.php
#####clean.php: Datenbereinigung und -aufbereitung nach dem Datenimport
- Liste mit Wahlpflichtkursen: src/php/cleaner/ids_elective.txt
- Liste mit welcher Kurse in welchen Plansemester: src/php/cleaner/scheduled_semester.csv


