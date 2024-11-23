package main

import (
	"bufio"
	"fmt"
	"os"

	"github.com/gocolly/colly/v2"
)

func getHackerNewsData() {
	var titles []string
	var dates []string
	var descriptions []string

	c := colly.NewCollector()

	c.OnHTML("h1.story-title a", func(h *colly.HTMLElement) {
		c.Visit(h.Request.AbsoluteURL(h.Attr("href")))
	})

	// Başlıkları al
	c.OnHTML("h2.home-title", func(h *colly.HTMLElement) {
		titles = append(titles, h.Text)
	})

	c.OnHTML("span.h-datetime", func(h *colly.HTMLElement) {
		dates = append(dates, h.Text)
	})

	c.OnHTML("div.home-desc", func(h *colly.HTMLElement) {
		descriptions = append(descriptions, h.Text)
	})

	c.OnRequest(func(r *colly.Request) {
		fmt.Println("Visiting", r.URL)
	})

	c.Visit("https://thehackernews.com")

	file, err := os.Create("hackernewsdata.txt")
	if err != nil {
		fmt.Println("Error creating file:", err)
		return
	}
	defer file.Close()

	writer := bufio.NewWriter(file)

	for i := 0; i < len(titles) && i < len(dates) && i < len(descriptions); i++ {

		_, err := writer.WriteString(fmt.Sprintf("Title: %s\nDate: %s\nDescription: %s\n\n", titles[i], dates[i], descriptions[i]))
		if err != nil {
			fmt.Println("Error writing to file:", err)
			return
		}
	}

	err = writer.Flush()
	if err != nil {
		fmt.Println("Error flushing buffer:", err)
		return
	}

	fmt.Println("Veriler Yazılıyor -------> hackernewsdata.txt ")
}

func getEksiData() {
	var topics []string
	var dates []string

	c := colly.NewCollector()

	c.OnHTML("ul.topic-list.partial li", func(h *colly.HTMLElement) {
		c.Visit(h.Request.AbsoluteURL(h.ChildAttr("a", "href")))
	})

	c.OnHTML("h1#title", func(h *colly.HTMLElement) {
		topics = append(topics, h.Text)
	})

	c.OnHTML("a.entry-date.permalink", func(h *colly.HTMLElement) {
		dates = append(dates, h.Text)
	})

	c.Visit("https://eksisozluk.com/")

	//Dosya Okuma Yazma işlemlerş

	file, err := os.Create("eksisozlukdata.txt")
	if err != nil {
		fmt.Println("Error creating file:", err)
		return
	}
	defer file.Close()

	writer := bufio.NewWriter(file)

	for i := 0; i < len(topics) && i < len(dates); i++ {

		_, err := writer.WriteString(fmt.Sprintf("Topics: %s\nDate: %s\n\n", topics[i], dates[i]))
		if err != nil {
			fmt.Println("Error writing to file:", err)
			return
		}
	}

	err = writer.Flush()
	if err != nil {
		fmt.Println("Error flushing buffer:", err)
		return
	}

	fmt.Println("Veriler Yazılıyor -------> eksisozlukdata.txt ")

}

func getNistData() {

	var cve []string
	var score []string

	c := colly.NewCollector()

	c.OnHTML("strong a", func(h *colly.HTMLElement) {
		cve = append(cve, h.Text)
	})

	c.OnHTML("a.label.label-warning", func(h *colly.HTMLElement) {
		score = append(score, h.Text)
	})

	c.Visit("https://nvd.nist.gov/")

	file, err := os.Create("nistlast20vuln.txt")
	if err != nil {
		fmt.Println("Error creating file:", err)
		return
	}
	defer file.Close()

	writer := bufio.NewWriter(file)

	for i := 0; i < len(cve) && i < len(score); i++ {
		_, err := writer.WriteString(fmt.Sprintf("CVE:ID: %s\nScore: %s\n\n", cve[i], score[i]))
		if err != nil {
			fmt.Println("Error writing to file:", err)
			return
		}
	}

	err = writer.Flush()
	if err != nil {
		fmt.Println("Error flushing buffer:", err)
		return
	}

	fmt.Println("Veriler Yazılıyor -------> nistlast20vuln.txt")

}

func main() {
	for {

		fmt.Println("--------- Menü ---------")
		fmt.Println("-1. HackerNews sitesinden Başlık , tarih ve açıklamaları  çek")
		fmt.Println("-2. EksiSözlük  sitesinden Topicleri ve Tarihleri çek")
		fmt.Println("-3. Last 20 Scored Vulnerability IDs on nist")
		fmt.Println("-4. Çıkış yap")
		fmt.Print("Bir seçenek girin (-1, -2, -3, -4): ")

		var choice string
		fmt.Scanln(&choice)

		switch choice {
		case "-1":
			fmt.Println("HackerNews sitesinden veri çekme işlemi başlatılıyor...\n")
			getHackerNewsData()
		case "-2":
			fmt.Println("İkinci bir site veri çekme işlemi başlatılıyor...\n")
			getEksiData()
		case "-3":
			fmt.Println("Üçüncü bir site veri çekme işlemi başlatılıyor...\n")
			getNistData()
		case "-4":
			fmt.Println("Uygulamadan çıkılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçenek, lütfen tekrar deneyin.\n")
		}
	}
}
