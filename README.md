# (For english [scroll down](#summary))

[1. Funkcje użytkownika](#1-funkcje-użytkownika)

[2. Funkcje kierownika](#2-funkcje-kierownika)

[2.1 Dodawanie użytkowników](#21-dodawanie-użytkowników)

[2.2 Zarządzanie rolami użytkowników](#22-zarządzanie-rolami-użytkowników)

[2.3 Zarządzanie uprawnieniami](#23-zarządzanie-uprawnieniami)

[2.4 Historia logowania i transakcji](#24-historia-logowania-i-transakcji)

[3. Instrukcja uruchomienia](#3instrukcja-uruchomienia)

Aplikacja do zarządzania kasą w sklepie. Napisana w języku PHP przy użyciu bazy danych MySQL. Aplikacja jest nastawiona na cyberbezpieczeństwo i posiada m.in system logowania dwuskładnikowego poprzez wysłanie kodu w mailu, hashowanie haseł zapisywanych do bazy danych, system hierarchicznych uprawnień dla użytkowników, ochronę przed SQL injection, wykorzystanie protokołu MQTT z Quality of Service.

# 1. Funkcje użytkownika

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/2d9780c1-a420-4bcd-914d-37b0440db465)

Aplikacja posiada pole logowania dla użytkownika. Następny widok jest zależny od uprawnień użytkownika.


![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/985937cb-7fd2-4bdc-9cef-f321c5418e42)

Po zalogowaniu jako zwykły użytkownik, kierownik może chwilowo przyznać nam uprawnienia klikając na przycisk "Zaloguj jako kierownik" (jest to przydatne przy usuwaniu błędnie dodanych produktów).


![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/14d405f5-9eb2-46a2-ad67-ec653b173e7b) ![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/f499bded-5719-46f2-bafd-dc498d7f248c) ![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/b2772798-668a-4831-a094-6e9ce8cb34ee)

Po zalogowaniu wybieramy produkty 

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/a53db695-a5ce-4ad0-9a4e-d0569a6d8bff)

Produkty zostały dodane do naszego koszyka.

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/c57b7833-1ab9-4f3c-ab48-488169bf193e) ![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/7abd6c83-14cd-4f85-8343-d8cb698c5aab)

Tutaj kończą się funkcje normalnego pracownika. Jeżeli chcemy usunąć produkt, musimy poprosić kierownika, który chwilowo przyzna nam uprawnienia. Wtedy może on usunąć produkt z koszyka i odebrać nam uprawnienia.

# 2.1 Dodawanie użytkowników

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/54ddfb2e-de0f-4634-a432-aa5ebca5b209)

Kierownik posiada więcej funkcji.

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/0df2ea9b-b83f-46bc-b542-1e17e2481648)

Kierownik może dodać nowego pracownika.

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/ddb64dcc-9cd2-4c8b-8f32-7f6e802b5c62)

Po dodaniu nowego pracownika jego hasło jest hashowane w bazie, by administrator bazy nie miał dostępu do jawnego hasła. Zapisywany jest również kod, który użytkownik dostał na maila w celu weryfikacji dwuskładnikowej.

# 2.2 Zarządzanie rolami użytkowników
![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/88d0fad0-2c85-4e9d-9f68-a4dda43b46a5)

# 2.3 Zarządzanie uprawnieniami

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/a0603f30-43e0-41f1-a586-e26b07a603e6)

Kierownik może zmieniać globalne uprawnienia. Może on dodawać i usuwać role oraz przydzielać poszczególne uprawnienia do ról.

# 2.4 Historia logowania i transakcji

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/318be085-379e-4c60-a15f-0914683df95f) ![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/abf17dba-1312-47a6-bfcf-a6a5f7ffa2c0)

Kierownik może przeglądać logowanie do systemu oraz próby logowania. Zapisywana jest nazwa użytkownika, data logowania oraz id sesji. Nieudane próby logowania są zapisywane w celu analizy prób ataków na system. Udane próby logowania są przydatne, gdy chcemy wiedzieć, który pracownik w danym czasie był odpowiedzialny za daną akcję. 




# 3.Instrukcja uruchomienia
Do uruchomienia aplikacji wystarczy interpreter php i sewver MySQL, w którym zostanie stworzona baza o nazwie mydb. Do bazy mydb należy zaimportować plik mydb.sql. Można zainstalować program xampp, który daje zarówno interpreter php jak i bazę MySQL.

![obraz](https://github.com/dariuszknappwr/checkout-management/assets/127883702/76bf2273-a320-4a1b-aaa8-003ad6f43c37)

Projekt zależy zapisać w katalogu instalacji xampp w folderze htdocs np. C:\xampp\htdocs\projekt, gdzie <i> projekt</i> to nazwa folderu, w którym znajduje się projekt. Następnie w przeglądarkę wpisujemy localhost/projekt/index.php



# Summary

[1.User features](#1-user-features)

[2. Manager Functions](#2-manager-functions)

[2.1 Adding Users](#21-adding-users)

[2.2 Managing User Roles](#22-managing-user-roles)

[2.3 Managing User Permissions](#23-managing-user-permissions)

[2.4 Login and Transaction History](#24-login-and-transaction-history)

[3. Running the Application](#3-running-the-application)

Application for managing the cash register in the store. Written in PHP using the MySQL database. The application is focused on cybersecurity and has, among others, a two-factor login system by sending a code in an e-mail, hashing of passwords saved to the database, a system of hierarchical authorizations for users, protection against SQL injection, and the use of the MQTT protocol with Quality of Service.

# 1. User Features

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/2d9780c1-a420-4bcd-914d-37b0440db465)

The application has a login field for the user. The next view depends on user permissions.


![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/985937cb-7fd2-4bdc-9cef-f321c5418e42)

After logging in as a regular user, the manager can temporarily grant us permissions by clicking the "Log in as manager" button (this is useful when removing incorrectly added products).


![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/14d405f5-9eb2-46a2-ad67-ec653b173e7b) ![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/F499BDED-5719-46F2-BAFD-DC498D7F248C) ![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/b2772798-668a-4831-a094-6e9ce8cb34ee)

After logging in, we select products

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/a53db695-a5ce-4ad0-9a4e-d0569a6d8bff)

The products have been added to our cart.

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/c57b7833-1ab9-4f3c-ab48-488169bf193e) ![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/7abd6c83-14cd-4f85-8343-d8cb698c5aab)

This is where the functions of a normal employee end. If we want to remove a product, we must ask the manager who will temporarily grant us permissions. Then he can remove the product from the cart and take away our rights.

# 2. Manager functions
# 2.1 Adding users

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/54ddfb2e-de0f-4634-a432-aa5ebca5b209)

The manager has more functions.

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/0df2ea9b-b83f-46bc-b542-1e17e2481648)

The manager can add a new employee.

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/ddb64dcc-9cd2-4c8b-8f32-7f6e802b5c62)

After adding a new employee, his password is hashed in the database so that the database administrator does not have access to the open password. The code that the user received by e-mail for two-factor verification is also saved.

# 2.2 User role management
![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/88d0fad0-2c85-4e9d-9f68-a4dda43b46a5)

# 2.3 Permission management

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/a0603f30-43e0-41f1-a586-e26b07a603e6)

The manager can change global permissions. He can add and remove roles and assign individual permissions to roles.

# 2.4 Login and transaction history

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/318be085-379e-4c60-a15f-0914683df95f) ![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/abf17dba-1312-47a6-bfcf-a6a5f7ffa2c0)

The manager can view system logins and login attempts. The username, login date and session ID are saved. Failed login attempts are logged for analysis of attempted attacks on the system. Successful login attempts are useful when we want to know which employee was responsible for a given action at a given time.




# 3. Starting instructions
To run the application, all you need is a php interpreter and MySQL sewver, in which a database called mydb will be created. The mydb.sql file must be imported into the mydb database. You can install xampp, which provides both a PHP interpreter and a MySQL database.

![image](https://github.com/dariuszknappwr/checkout-management/assets/127883702/76bf2273-a320-4a1b-aaa8-003ad6f43c37)

The project should be saved in the xampp installation directory in the htdocs folder, e.g. C:\xampp\htdocs\project, where <i>project</i> is the name of the folder where the project is located. Then enter localhost/project/index.php in the browser
