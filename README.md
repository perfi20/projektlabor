# projektlabor https://perfi.hu/
PHP alapú blog alkalmazás Projekt labor és tervezés egyetemi tárgy keretében.

Az alkalmazás megjelenítése Bootstrap 5 alapon történik, számos beépített funkcióját kihasználva.

Az alkalmazásban a felhasználó blogbejegyzéseket tud megtekinteni különböző témákban, regisztrálást követően maga is tud létrehozni és kezelni őket.

A kezdőoldalon kiemelt bejegyzések, 3 véletlenszerű bejegyzés, illetve a legutóbbi 3 bejegyzés kerül megjelenítésre.
A fenti navigációban témák szerint lehet megjeleníteni a blogokat, illetve a közzétevő nevére kattintva név szerint lehet szűrni.

Sok találat esetén dinamikus lapszámozás működik, az api-tól is dinamikusan kéri le a cikkeket.

Regisztrálni egyedi felhasználónévvel és eamil címmel lehet.

Bejelentkezés után megjelenik egy profil menüpont, ahol többek közt kezelhetjük adatainkat, kezlhetjük bejegyzéseinket, illetve újat is tudunk létrehozni.

Bejegyzést létrehozni egy dinamikusan bővíthető űrlappal tudunk, különféle elemeket tudunk hozzáadni a tartalomhoz.

Admin jogosultságú felhasználók külön oldalon tudják kezelni a bejegyzéseket és felhasználókat általános statisztikákkal együtt.

Az alkalmazás REST API-val rendelkezik, az autentikáció JWT tokenekkel történik. A bejelentkezési állapot mentése session tárolón keresztül működik, a böngésző bezárásáig, vagy manuális kijelentkezésig belépve maradunk.

Az API PDO-val és tárolt eljárásokkal dolgozik, a végpontokat egy univerzális cURL függvénnyel lehet elérni.
Az API által adott üzeneteket és állapotokat a Bootstrap Toast funkciója jeleníti meg.