# Görev Açıklaması 

Takım üyelerinin Go Programlama dili kullanarak CLI üzerinde çalışan bir giriş
sistemi ve bu sistemi loglayan bir uygulama yazmaları beklenmektedir.

İsterler:
1) Kullanıcı Bilgileri ve Log sistemi
a. Admin ve Müşteri adında 2 adet kullanıcı tipi olmalı
b. Terminale 0 yazılırsa admin,1 yazılırsa müşteri olarak giriş yapılmalı
c. Başarılı veya hatalı girişler log.txt dosyasına anlık olarak kaydedilmeli

2) Yetkilendirme Sistemi
Admin Yetkileri:
a. Müşteri ekleme
b. Müşteri silme
c. Log listeleme
Müşteri Yetkileri:
a. Profil görüntüleme
b. Şifre değiştirebilme






# Ödev Tamamlanma Adımları



## 1. Go Öğrenimi
- Go dilinin temelleri öğrenildi; temel syntax, veri tipleri, ve fonksiyon yapıları incelendi.

## 2. Go Kurulumu
- Go ortamı kuruldu.
- Kurulum hakkında bilgi:
- **Go kurulumu**: Go dilini [buradan](https://golang.org/doc/install) indirip kurabilirsiniz. Kurulum sonrası `go version` komutu ile kurulumun doğruluğunu kontrol edin.


## 4. Kullanıcı Tipleri (UserType) Oluşturma
- Kullanıcılar için `admin` ve `customer` olmak üzere iki tip oluşturuldu.
- Her kullanıcı tipi, `UserType` özelliği ile ayırt edildi.

## 5. Yapı Tanımları (`struct`) ve Slice Kullanımı

- `User` adında bir `struct` tanımlandı:
  ```go
  type User struct {
      Username string
      Password string
      UserType string
  }
  

  




  
Kullanıcılar bir slice içinde saklanarak yönetildi: var users []User


 6. Fonksiyonlar
Kullanıcı Yükleme (loadUsers): Kayıtlı kullanıcıları dosyadan okuma işlemi.
Kullanıcı Kaydetme (saveUsers): Kullanıcıları dosyaya yazma işlemi.
Giriş (login): Kullanıcıların kimlik doğrulaması.
Log Yazma (writeLog): Kullanıcı işlemlerini kaydetme.
Admin ve Müşteri Sayfaları: Her kullanıcı tipi için ayrı menüler ve işlevler sağlandı.
Log Yönetimi
log.txt dosyasına her işlem kaydedildi (giriş, müşteri ekleme/silme vb.).


----------------------------------------------------------------------------
# Credentials

``username:password , admin:admin
``
