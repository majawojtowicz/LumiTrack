# LumiTrack
<img width="1867" height="953" alt="image" src="https://github.com/user-attachments/assets/b35f8caf-3926-4226-ba18-9e364f8f5685" />
<img width="1897" height="949" alt="image" src="https://github.com/user-attachments/assets/5394058d-823f-4345-87e7-a50e74981904" />

<img width="1879" height="959" alt="image" src="https://github.com/user-attachments/assets/0bf5bfe2-8564-4796-95c3-f9a7a9f127a7" />
<img width="1888" height="856" alt="image" src="https://github.com/user-attachments/assets/ee5138e9-5a84-42b2-a55b-dc192b9d484b" />
<img width="1895" height="870" alt="image" src="https://github.com/user-attachments/assets/e78c322e-6f78-4c8e-b5b6-9877cdc13ecb" />
<img width="1901" height="857" alt="image" src="https://github.com/user-attachments/assets/7e06687c-3df0-4767-8d28-af8e196500f9" />

<img width="1563" height="941" alt="erd diagram pgerd" src="https://github.com/user-attachments/assets/1c0a69cf-d84b-4976-a576-d94cbade9709" />


<img width="442" height="946" alt="image" src="https://github.com/user-attachments/assets/e3870efb-ab70-4471-b737-a24336c54ced" />

Instrukcje uruchomienia
1.Wymagania wstepne:
Zainstalowany Docker Desktop
Zainstalowany Git
2. Zmienne srodowiskowee znajduja sie w Database.php i config.php
3.W glownym folderze projektu nalezy wykonac to polecenie w terminalu: docker-compose up -d --build
4. Aplikacja bedzie dostepna pod adresem: http://localhost:8080
5. Uruchomienie testow:
  1.Testy integracyjne: docker-compose exec php sh /app/tests/testEndpoints.sh
  2.Testy PHPUnit: docker-compose exec php /app/vendor/bin/phpunit --bootstrap /app/vendor/autoload.php /app/tests
6. Dostep do DB: 
  1. Wejsc na: http://localhost:5050
  2. Zalogowac sie danymi z pliku config.php
  3. Doadac nowy serwer

Scenariusze Testowe:
1. Rejestracja i Autentykacja
   1. Zarejestruj nowego uzytkownika
   2. System haszuje haslo, tworzy rekord w users oraz autmatyczny profil w user_profiles.
   3. Przekierowanie do logowania
   4. Zaloguj sie na nowe konto
   5. Sprawdzenie roli uzytkownika
2. Operacje Crud
   1.Dodawanie nowego wpisu w dashboard
   2.Czytanie nowych wpisow w zakladce History
   3.Filtr w zakladce History
   4.Usuwanie wpisow 
3. Błąd 401
   1. Sprobuj wejsc na zakladke /dashboard nie bedac zalogowanym
   <img width="1378" height="606" alt="image" src="https://github.com/user-attachments/assets/38c6acfd-eb93-46af-a034-90c985520fd5" />
4. Bład 403
   1. Sprobuj wejsc na zakladke /admin nie bedac ADMIN
   <img width="634" height="432" alt="image" src="https://github.com/user-attachments/assets/79cebf5e-a9a0-4a73-ac19-c4b42a7f9058" />
5. Widok v_admin_activity_log
   1. W panelu Admin wyswietlaja sie dane o aktywnosci uzytkownika
6. Trigger trg_blcked_user_entry
   1. Jako ADMIN zablokuj uzytkownika
   2. Sprobuj bedac zalogowanym jako ten uzytkownik dodac wpis

Checklista:
1. Wzorzec MVC: Pełne rozdzielenie logiki (Kontrolery), danych (Repozytoria) i widoków (HTML/CSS)
2. Routing
3. Haszowanie haseł: przy uzyciu password_bcrypt
4. Zarzadanie sesja
5. Podzial na role user i admin
6. Dynamiczny filtr na stronie /history
7. Obsluga bledow
8. Testy jednostkowe
9. Testy Integracyjne
  

   




