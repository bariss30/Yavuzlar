# YAVUZLAR Restaurant_APP PROJESİ



Kullanım : 

``
git clone https://github.com/bariss30/Yavuzlar.git/Task3/my_app``<br>
``cd Task3/my_app/docker`` <br>
``docker-compose up -d``<br>
`` http://127.0.0.1``<br>

Docker Engine Yüklü Olduğundan Emin Olun




                                                       ...

Uygulama Bilgileri:<br>
=> Admin:<br>
Kullanıcı Adı: baris<br>
Şifre: baris123<br>
=> Firma:<br>
Kullanıcı Adı: kullanıcı1<br>
Şifre: kullanıcı1<br>
=> Kullanıcı:<br>
Kullanıcı Adı: kullanıcı2<br>
Şifre: kullanıcı2<br>
=> Veritabanı:<br>
Kullanıcı Adı: root<br>
Şifre: rootpassword<br>









                                                       ...



=> Yardımcı Docker Komutları 

 1. Çalışan tüm konteynerleri durdurma
docker stop $(docker ps -q)

 2. Tüm konteynerleri silme
docker rm $(docker ps -a -q)

 3. Docker Compose ile yeniden inşa ederek tüm servislere başlama
docker-compose up -d --build

 4. Çalışan tüm konteynerleri durdurma (tekrar)
docker stop $(docker ps -q)

 5. Docker Compose ile servisleri başlatma (arka planda)
docker-compose up -d

 6. Çalışan konteynerlerin listesini gösterme
docker ps

 7. Docker Compose ile servisleri ve volümleri kapatma
docker-compose down -v


                                                       ...


Eksikler ! 
Şifreleme Algoritması yanlış yapılandırması ! => Admin Panelinden Kullanıcı Oluşturma Eksiği 











