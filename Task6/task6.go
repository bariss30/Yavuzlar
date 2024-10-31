package main

import (
	"bufio" //yapay zeka fikir alıdnı
	"fmt"
	"log"
	"os"
)

type User struct {
	Username string
	Password string
	UserType string
}

var users []User      // yapay zeka fikir alındı
var currentUser *User //yapay zeka fikir alındı

func init() { // Yardım Alındı
	loadUsers()
}

// Func Start

func loadUsers() {
	file, err := os.Open("musteriler.txt")
	if err != nil {
		if os.IsNotExist(err) {
			return // Dosya yoksa, yeni oluşturulacak
		}
		log.Fatal(err)
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		line := scanner.Text()
		var username, password, userType string
		fmt.Sscanf(line, "Username: %s, Password: %s, UserType: %s", &username, &password, &userType)
		users = append(users, User{Username: username, Password: password, UserType: userType})
	}
	if err := scanner.Err(); err != nil {
		log.Fatal(err)
	}
}

func saveUsers() {
	file, err := os.Create("musteriler.txt")
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	for _, user := range users {
		if user.UserType == "customer" {
			_, err := file.WriteString(fmt.Sprintf("Username: %s, Password: %s, UserType: %s\n", user.Username, user.Password, user.UserType))
			if err != nil {
				log.Fatal(err)
			}
		}
	}
}

// Log Dosyası Hazırlama
func writeLog(action, username, status string) {
	file, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	logEntry := fmt.Sprintf("Action: %s, Username: %s, Status: %s\n", action, username, status)
	_, err = file.WriteString(logEntry)
	if err != nil {
		log.Fatal(err)
	}
}

// Admin sayfası Hazırlamak
func adminPage() {
	fmt.Println("\nAdmin Sayfasına Hoş Geldiniz!")

	for {
		fmt.Println("\nAdmin Menüsü:")
		fmt.Println("1. Müşteri Ekle")
		fmt.Println("2. Müşteri Sil")
		fmt.Println("3. Logları Listele")
		fmt.Println("4. Çıkış")

		var choice string
		fmt.Print("Seçiminiz: ")
		fmt.Scanln(&choice)

		switch choice {
		case "1":
			addCustomer()
		case "2":
			deleteCustomer()
		case "3":
			listLogs()
		case "4":
			return
		default:
			fmt.Println("Geçersiz seçim!")
		}
	}
}

// Müşteri sayfası Hazırlamaa
func customerPage() {
	fmt.Println("Müşteri Sayfasına Hoş Geldiniz!")

	for {
		fmt.Println("\nMüşteri Menüsü:")
		fmt.Println("1. Profil Görüntüle")
		fmt.Println("2. Şifre Değiştir")
		fmt.Println("3. Çıkış")

		var choice string
		fmt.Print("Seçiminiz: ")
		fmt.Scanln(&choice)

		switch choice {
		case "1":
			viewProfile()
		case "2":
			changePassword()
		case "3":
			return
		default:
			fmt.Println("Geçersiz seçim!")
		}
	}
}

func viewProfile() {
	fmt.Printf("\nProfil Bilgileri:\n")
	fmt.Printf("Kullanıcı Adı: %s\n", currentUser.Username)
	fmt.Printf("Kullanıcı Tipi: %s\n", currentUser.UserType)
	fmt.Printf("Kullanıcı Şifresi: %s\n", currentUser.Password)
}

func changePassword() {
	var newPassword string
	fmt.Print("Yeni şifre: ")
	fmt.Scanln(&newPassword)
	currentUser.Password = newPassword
	saveUsers()
	fmt.Println("Şifreniz başarıyla değiştirildi.")
}

// Log yazdırma
func listLogs() {
	file, err := os.Open("log.txt")
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}
	if err := scanner.Err(); err != nil {
		log.Fatal(err)
	}
}

func addCustomer() {
	scanner := bufio.NewScanner(os.Stdin)

	fmt.Print("Yeni müşteri kullanıcı adı: ")
	scanner.Scan()
	username := scanner.Text()

	// aynı kullanıcı adı ile kayıt engelle

	for _, user := range users {
		if user.Username == username {
			fmt.Println("Bu kullanıcı adı zaten mevcut!")
			return
		}
	}

	fmt.Print("Şifre: ")
	scanner.Scan()
	password := scanner.Text()

	users = append(users, User{
		Username: username,
		Password: password,
		UserType: "customer",
	})
	saveUsers()
	writeLog("Add Customer", username, "Success")
	fmt.Println("Müşteri başarıyla eklendi!")
}

func deleteCustomer() {
	var username string
	fmt.Print("Silinecek müşteri kullanıcı adı: ")
	fmt.Scanln(&username)

	for i, user := range users {
		if user.Username == username && user.UserType == "customer" {
			users = append(users[:i], users[i+1:]...) //append yardım alındı
			saveUsers()
			writeLog("Delete Customer", username, "Success")
			fmt.Println("Müşteri başarıyla silindi!")
			return
		}
	}
	fmt.Println("Müşteri bulunamadı!")
}

// LOGiN

func login(userType string, user *User) {
	var username, password string

	fmt.Print("Kullanıcı adı: ")
	fmt.Scanln(&username)
	fmt.Print("Şifre: ")
	fmt.Scanln(&password)

	if username == user.Username && password == user.Password {
		fmt.Println("Giriş başarılı!")
		writeLog("Login", username, "Success")
		currentUser = user // Geçerli kullanıcıyı güncelle

		if user.UserType == "admin" {
			adminPage()
		} else {
			customerPage()
		}
	} else {
		fmt.Println("Giriş başarısız! Kullanıcı adı veya şifre yanlış.")
		writeLog("Login", username, "Failed")
	}
}

func main() {
	adminUser := &User{Username: "admin", Password: "admin", UserType: "admin"}

	for {
		fmt.Println("\nGiriş Tipi Seçin:")
		fmt.Println("0: Admin")
		fmt.Println("1: Müşteri")
		fmt.Println("2: Çıkış")

		var choice string
		fmt.Print("Seçiminiz: ")
		fmt.Scanln(&choice)

		switch choice {
		case "0":
			login("admin", adminUser)

		case "1":
			var username string
			fmt.Print("Müşteri kullanıcı adı: ")
			fmt.Scanln(&username)

			var foundUser *User
			for i := range users {
				if users[i].Username == username && users[i].UserType == "customer" {
					foundUser = &users[i]
					break
				}
			}

			if foundUser != nil {
				login("customer", foundUser)
			} else {
				fmt.Println("Müşteri bulunamadı!")
			}

		case "2":
			fmt.Println("Programdan çıkılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçim!")
		}
	}
}
