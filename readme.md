# Audioteka: zadanie rekrutacyjne

## Rozwiązanie
**1. Chcemy móc dodawać do koszyka ten sam produkt kilka razy, o ile nie zostanie przekroczony sumaryczny limit sztuk produktów. Teraz to nie działa.**

Należało z relacji ManyToMany, utworzyć entity CartProduct z dwiema relacjami ManyToOne i dodatkowym polem. Można byłoby dodać pole "quantity", które podliczałoby duplikaty. Zdecydowałem się jednak na dodanie identyfikatora aby zachować strukturę odpowiedzi z endpointa. Jeżeli wymagania dotyczące endpointa API miałby zostać zmienione, można wtedy dodać pole quantity.

Postanowiłem zrefaktoryzować interface App\Service\Cart\Cart, ponieważ uznałem, że w momencie, gdy do produktu w koszyku, miałyby zostać dodane dodatkowe parametry, będzie to wygodniejsze do obsługi. Wymagało to też zmiany w testach.

Ponieważ zdecydowałem się dodać generowany identyfikator Id do CartProduct, koszyk zwraca produkty w kolejności według tego identyfikatora (wedlug kolejności dodania), więc należało poprawić test test_shows_cart.

****

**2. Limit koszyka nie zawsze działa. Wprawdzie, gdy podczas naszych testów dodajemy czwarty produkt do koszyka to dostajemy komunikat `Cart is full.`, ale pomimo tego i tak niektóre koszyki w bazie danych mają po cztery produkty.**

Ze względu na to, że zadanie dodania produktu do koszyka jest obsługiwane przez kolejkę asynchroniczną, może dojść do przypadku, w którym zadanie nie zostanie jeszcze obsłużone, a kolejny request będzie odpytywał nieaktualny stan koszyka. Aby temu zapobiec, zamieniłem kolejkę na synchroniczną oraz utworzyłem locka, który będzie czekał, aż poprzednie zadanie zostanie wykonane.

Dodatkowym zabezpieczeniem będzie, dodanie w CartRepository,  sprawdzenia czy koszyk jest pełen. Użycie tylko tego drugiego rozwiązania byłoby niewystarczające, ponieważ mimo, że zablokowalibyśmy możliwość dodania produktów ponad limit, to użytkownik wciąż mógłby otrzymywać błędną odpowiedź.

****

**3. Najnowsze (ostatnio dodane) produkty powinny być dostępne na początkowych stronach listy produktów.**

Dodano kolumnę created z datą utworzenia oraz umożliwiona sortowanie w klasie ProductRepository. Uwzględniono możliwość sortownia w przyszłości po innych parametrach, wraz z sprawdzeniem czy parametr istnieje w entity.  

Na ten moment wybór sortowania znajduje się w controllerze, tworząc obiekt OrderBy. W przyszłości można byłoby utworzyć traita, który tworzył by ten obiekt na podstawie parametrów sortowania z query, tak by można było zaimplementować to również w innych kontrolerach.

****

**4. Musimy mieć możliwość edycji produktów. Czasami w nazwach są literówki, innym razem cena jest nieaktualna.**

Utworzono testy, request oraz funkcję w repository i interfejsie umożliwiające edycję produktu. 

Można byłoby jeszcze dodać moduł symfony/validator i na bazie Constraints'ów i atrybutów zrobić walidację entity Product aby te same warunki wykorzystywać przy dodawaniu i edycji.

****

## Instalacja

Do uruchomienia wymagany jest `docker` i `docker-compose`

1. Zbuduj obrazy dockera `docker-compose build`
1. Zainstaluj zależności `docker-compose run --rm php composer install`.
1. Zainicjalizuj bazę danych `docker-compose run --rm php php bin/console doctrine:schema:create`.
1. Zainicjalizuj kolejkę Messengera `docker-compose run --rm php php bin/console messenger:setup-transports`.
1. Uruchom serwis za pomocą `docker-compose up -d`.

Jeśli wszystko poszło dobrze, serwis powinien być dostępny pod adresem 
[https://localhost](https://localhost).

Przykładowe zapytania (jak komunikować się z serwisem) znajdziesz w [requests.http](./requests.http).

Testy uruchamia polecenie `docker-compose run --rm php php bin/phpunit`

## Oryginalne wymagania dotyczące serwisu

Serwis realizuje obsługę katalogu produktów oraz koszyka. Klient serwisu powinien móc:

* dodać produkt do katalogu,
* usunąć produkt z katalogu,
* wyświetlić produkty z katalogu jako stronicowaną listę o co najwyżej 3 produktach na stronie,
* utworzyć koszyk,
* dodać produkt do koszyka, przy czym koszyk może zawierać maksymalnie 3 produkty,
* usunąć produkt z koszyka,
* wyświetlić produkty w koszyku, wraz z ich całkowitą wartością.

Kod, który masz przed sobą, stara się implementować te wymagania z pomocą `Symfony 6.0`.

## Zadanie

Użytkownicy i testerzy serwisu zgłosili następujące problemy i prośby:

* Chcemy móc dodawać do koszyka ten sam produkt kilka razy, o ile nie zostanie przekroczony sumaryczny limit sztuk produktów. Teraz to nie działa.
* Limit koszyka nie zawsze działa. Wprawdzie, gdy podczas naszych testów dodajemy czwarty produkt do koszyka to dostajemy komunikat `Cart is full.`, ale pomimo tego i tak niektóre koszyki w bazie danych mają po cztery produkty. 
* Najnowsze (ostatnio dodane) produkty powinny być dostępne na początkowych stronach listy produktów. 
* Musimy mieć możliwość edycji produktów. Czasami w nazwach są literówki, innym razem cena jest nieaktualna.

Prosimy o naprawienie / implementację.

PS. Prawdziwym celem zadania jest oczywiście kawałek kodu, który możemy ocenić, a potem porozmawiać o nim w czasie interview "twarzą w twarz". Przy czym pamiętaj, że liczy się nie tylko napisany kod PHP, ale także umiejętność przedstawienia czytelnego rozwiązania, użycia odpowiednich narzędzi (chociażby systemu wersjonowania), udowodnienia poprawności rozwiązania (testy) itd. 

To Twoja okazja na pokazanie umiejętności, więc jeśli uważasz, że w kodzie jest coś nie tak, widzisz więcej błędów, coś powinno być zaimplementowane inaczej, możesz do listy zadań dodać opcjonalny refactoring, albo krótko wynotować swoje spostrzeżenia (może przeprowadzić coś w rodzaju code review?).

Powodzenia!

