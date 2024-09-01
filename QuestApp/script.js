let questions = [
    {
        questions: "JavaScript'te bir değişken tanımlamak için hangi anahtar kelimeler kullanılır?",
        answer: [
            { text: "var, let, const", correct: true },
            { text: "int, float, double", correct: false },
            { text: "public, private, protected", correct: false },
            { text: "if, else, switch", correct: false },
        ],
        difficulty: "easy" 
    },
    {
        questions: "JavaScript'te bir değişken tanımlamak için hangi anahtar kelimeler kullanılır?",
        answer: [
            { text: "var, let, const", correct: true },
            { text: "int, float, double", correct: false },
            { text: "public, private, protected", correct: false },
            { text: "if, else, switch", correct: false },
        ],
        difficulty: "medium" 
    },
];

const questionsElement = document.getElementById("questions");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");

let currentQuestionsIndex = 0;
let score = 0;




function startQuiz() {
    currentQuestionsIndex = 0;
    score = 0;
    nextButton.innerHTML = "Next";
    showQuestions();
}






function showQuestions() {
    resetState();
    let currentQuestions = questions[currentQuestionsIndex];


    
    let questionsNo = currentQuestionsIndex + 1;
    questionsElement.innerHTML = `${questionsNo}. ${currentQuestions.questions} (Zorluk: ${currentQuestions.difficulty})`;
    currentQuestions.answer.forEach(answer => {
        const button = document.createElement("button");
        button.innerHTML = answer.text;
        button.classList.add("btn");
        answerButtons.appendChild(button);
        if (answer.correct) {
            button.dataset.correct = answer.correct;
        }
        button.addEventListener("click", selectAnswer);
    });
}






function resetState() {
    nextButton.style.display = "none";
    while (answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}




function selectAnswer(e) {
    const selectedBtn = e.target;
    const isCorrect = selectedBtn.dataset.correct === "true";

    if (isCorrect) {
        selectedBtn.classList.add("correct");
        score++;
    } else {
        selectedBtn.classList.add("incorrect");
    }

    Array.from(answerButtons.children).forEach(button => {
        if (button.dataset.correct === "true") {
            button.classList.add("correct");
        }
        button.disabled = true;
    });

    nextButton.style.display = "block";
}










function showScore() {
    resetState();
    questionsElement.innerHTML = `You scored ${score} out of ${questions.length}!`;
    nextButton.innerHTML = "Play Again";
    nextButton.style.display = "block";
}









function handleNextButton() {
    currentQuestionsIndex++;
    if (currentQuestionsIndex < questions.length) {
        showQuestions();
    } else {
        showScore();
    }
}








nextButton.addEventListener("click", () => {
    if (currentQuestionsIndex < questions.length) {
        handleNextButton();
    } else {
        startQuiz();
    }
});








function addQuestion() {
    const newQuestionText = prompt("Yeni soru?");
    if (!newQuestionText) {
        alert("Soru boş olamaz");
        return;
    }

    const newAnswers = [];
    for (let i = 1; i <= 4; i++) {
        const answerText = prompt(`Cevap ${i} nedir?`);
        if (!answerText) {
            alert("Cevap boş olamaz");
            return;
        }
        newAnswers.push({ text: answerText, correct: false });
    }

    const correctAnswerIndex = parseInt(prompt("Doğru cevabın numarası 1-4")) - 1;
    if (correctAnswerIndex >= 0 && correctAnswerIndex < newAnswers.length) {
        newAnswers[correctAnswerIndex].correct = true;
    } else {
        alert("Geçersiz doğru cevap numarası!");
        return;
    }

    const difficulty = prompt("Zorluk seviyesini girin (easy, medium, hard):");
    if (!["easy", "medium", "hard"].includes(difficulty)) {
        alert("Geçersiz zorluk seviyesi!");
        return;
    }

    questions.push({ questions: newQuestionText, answer: newAnswers, difficulty: difficulty });
    alert("Yeni soru eklendi");
}
const addQuestionsButton = document.getElementById("add-question-btn");
addQuestionsButton.addEventListener("click", addQuestion);





function dellQuestions() {
    alert("Silme işlemi Yaparken dikkat ediniz !!!");

    let questionList = "Silmek istediğiniz sorunun numarasını seçin:";
    questions.forEach((q, index) => {
        questionList += `${index + 1}: ${q.questions}\n`;
    });

    const questionIndex = prompt(questionList) - 1;

    if (questionIndex >= 0 && questionIndex < questions.length) {
        questions.splice(questionIndex, 1);
        alert("Soru silindi!");
    
        if (currentQuestionsIndex >= questions.length) {
            currentQuestionsIndex = questions.length - 1;
        }
        showQuestions();
    } else {
        alert("Geçersiz bir indeks seçtiniz.");
    }
}
const dellQuestionsButton = document.getElementById("del-question-btn");
dellQuestionsButton.addEventListener("click", dellQuestions);







function editQuestions() {
    let questionList = "Düzenlemek istediğiniz sorunun numarasını seçin:\n";
    questions.forEach((q, index) => {
        questionList += `${index + 1}: ${q.questions} (Zorluk: ${q.difficulty})\n`;
    });

    const questionIndex = prompt(questionList) - 1;

    if (questionIndex >= 0 && questionIndex < questions.length) {
        const currentQuestion = questions[questionIndex].questions;
        const newQuestion = prompt("Yeni soruyu giriniz:", currentQuestion);

     

        questions[questionIndex].questions = newQuestion;

        let answerList = "Düzenlemek istediğiniz cevabın numarasını seçin:\n";
        questions[questionIndex].answer.forEach((a, index) => {
            answerList += `${index + 1}: ${a.text}\n`;
        });

        let answerIndex = prompt(answerList) - 1;

        while (answerIndex >= 0 && answerIndex < questions[questionIndex].answer.length) {
            const currentAnswer = questions[questionIndex].answer[answerIndex].text;
            const newAnswer = prompt("Yeni cevabı girin:", currentAnswer);

           

            const correctAnswer = confirm("Bu cevabı doğru olarak işaretlemek istiyor musun");
            questions[questionIndex].answer.forEach(a => a.correct = false); 
            questions[questionIndex].answer[answerIndex] = {
                text: newAnswer,
                correct: correctAnswer
            };

            answerList = "Düzenlemek istediğiniz cevabın numarasını seçin:\n";
            questions[questionIndex].answer.forEach((a, index) => {
                answerList += `${index + 1}: ${a.text} \n`;
            });

            answerIndex = prompt(answerList) - 1;
        }

        
        const newDifficulty = prompt("Yeni zorluk seviyesini girin (easy, medium, hard):");
        if (["easy", "medium", "hard"].includes(newDifficulty)) {
            questions[questionIndex].difficulty = newDifficulty;
        } else {
            alert("Geçersiz zorluk seviyesi");
        }

        alert("Soru ve cevaplar başarıyla güncellendi");
        showQuestions(); 
    } else {
        alert("Geçersiz bir indeks seçtini");
    }
}
const editQuestionsButton = document.getElementById("edit-question-btn");
editQuestionsButton.addEventListener("click", editQuestions);








function searchQuestions() {
    const searchTerm = prompt("Aramak istediğiniz kelimeyi gir");
    

    const searchResults = questions.filter(q => q.questions && q.questions.includes(searchTerm));

    if (searchResults.length === 0) {
        alert("Arama terimi ile eşleşen soru bulunamadı.");
        return;
    }

    let resultList = "Arama sonuçları:\n";
    searchResults.forEach((q, index) => {
        resultList += `${index + 1}: ${q.questions} (Zorluk: ${q.difficulty})\n`;
        q.answer.forEach((a, aIndex) => {
            resultList += `  Cevap ${aIndex + 1}: ${a.text}\n`;
        });

    });

    alert(resultList);
}
const searchQuestionButton = document.getElementById("search-question-btn");
searchQuestionButton.addEventListener("click", searchQuestions);







startQuiz();
