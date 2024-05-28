<?php
// seleccionando todos los elementos requeridos
$start_btn = '<button class="start_btn"></button>';
$info_box = '<div class="info_box"></div>';
$exit_btn = '<div class="buttons"><button class="quit"></button></div>';
$continue_btn = '<div class="buttons"><button class="restart"></button></div>';
$quiz_box = '<div class="quiz_box"></div>';
$result_box = '<div class="result_box"></div>';
$option_list = '<div class="option_list"></div>';
$time_line = '<header><div class="time_line"></div></header>';
$timeText = '<div class="timer"><p class="time_left_txt"></p></div>';
$timeCount = '<div class="timer"><p class="timer_sec"></p></div>';

// si se hace clic en el botón Iniciar prueba
$start_btn.onclick = '() => {
    $info_box.classList.add("activeInfo"); // mostrar cuadro de información
}';

// si se hace clic en el botón Salir del cuestionario
$exit_btn.onclick = '() => {
    $info_box.classList.remove("activeInfo"); // ocultar cuadro de información
}';

// si se hace clic en el botón continuar prueba
$continue_btn.onclick = '() => {
    $info_box.classList.remove("activeInfo"); // ocultar cuadro de información
    $quiz_box.classList.add("activeQuiz"); // mostrar cuadro de cuestionario
    showQuetions(0); // llamar a la función showQestions
    queCounter(1); // pasar 1 parámetro a queCounter
    startTimer(20); // llamar a la función startTimer
    startTimerLine(0); // llamar a la función startTimerLine
}';

$timeValue = 20;
$que_count = 0;
$que_numb = 1;
$userScore = 0;
$counter;
$counterLine;
$widthValue = 0;

// si se hace clic en el botón Reiniciar cuestionario
$restart_quiz = '<div class="buttons"><button class="restart"></button></div>';
$quit_quiz = '<div class="buttons"><button class="quit"></button></div>';

$restart_quiz.onclick = '() => {
    $quiz_box.classList.add("activeQuiz"); // mostrar cuadro de cuestionario
    $result_box.classList.remove("activeResult"); // ocultar cuadro de resultados
    $timeValue = 20;
    $que_count = 0;
    $que_numb = 1;
    $userScore = 0;
    $widthValue = 0;
    showQuetions($que_count); // llamar a la función showQestions
    queCounter($que_numb); // pasar el valor que_numb a queCounter
    clearInterval($counter); // borrar contador
    clearInterval($counterLine); // borrar counterLine
    startTimer($timeValue); // llamar a la función startTimer
    startTimerLine($widthValue); // llamar a la función startTimerLine
    $timeText.textContent = "Tiempo restante"; // cambiar el texto de timeText a Tiempo restante
    $next_btn.classList.remove("show"); // ocultar el botón siguiente
}';

// si se hace clic en el botón Salir del cuestionario
$quit_quiz.onclick = '() => {
    window.location.reload(); // recargar la ventana actual
}';

$next_btn = '<footer><button class="next_btn"></button></footer>';
$bottom_ques_counter = '<footer><div class="total_que"></div></footer>';

// si se hace clic en el botón Next Que
$next_btn.onclick = '() => {
    if($que_count < count($questions) - 1){ // si el contador de pregunta es menor que la longitud total de preguntas
        $que_count++; // incrementar el valor del contador de pregunta
        $que_numb++; // incrementar el valor de que_numb
        showQuetions($que_count); // llamar a la función showQestions
        queCounter($que_numb); // pasar el valor de que_numb a queCounter
        clearInterval($counter); // borrar contador
        clearInterval($counterLine); // borrar counterLine
        startTimer($timeValue); // llamar a la función startTimer
        startTimerLine($widthValue); // llamar a la función startTimerLine
        $timeText.textContent = "Tiempo restante"; // cambiar el timeText a Tiempo restante
        $next_btn.classList.remove("show"); // ocultar el botón siguiente
    }else{
        clearInterval($counter); // borrar contador
        clearInterval($counterLine); // borrar counterLine
        showResult(); // llamar a la función showResult
    }
}';

// obtener preguntas y opciones de la matriz
function showQuetions($index){
    $que_text = '<div class="que_text"></div>';

    // creando una nueva etiqueta span y div para la pregunta y la opción y pasando el valor usando el índice de matriz
    $que_tag = '<span>'. $questions[$index]["numb"] . ". " . $questions[$index]["question"] .'</span>';
    $option_tag = '<div class="option"><span>'. $questions[$index]["options"][0] .'</span></div>'
    . '<div class="option"><span>'. $questions[$index]["options"][1] .'</span></div>'
    . '<div class="option"><span>'. $questions[$index]["options"][2] .'</span></div>'
    . '<div class="option"><span>'. $questions[$index]["options"][3] .'</span></div>';
    $que_text.innerHTML = $que_tag; // agregar nueva etiqueta span dentro de que_tag
    $option_list.innerHTML = $option_tag; // agregar nueva etiqueta div dentro de option_tag
    
    $option = $option_list.querySelectorAll(".option");

    // establecer el atributo onclick para todas las opciones disponibles

    for($i=0; $i < count($option); $i++){
        $option[$i].setAttribute("onclick", "optionSelected(this)");
    }
}

// creando las nuevas etiquetas div que para los íconos
$tickIconTag = '<div class="icon tick"><i class="fas fa-check"></i></div>';
$crossIconTag = '<div class="icon cross"><i class="fas fa-times"></i></div>';

// si el usuario hizo clic en la opción
function optionSelected($answer){
    clearInterval($counter); // borrar contador
    clearInterval($counterLine); // borrar counterLine

    $userAns = $answer.textContent; // obtener la opción seleccionada por el usuario
    $correcAns = $questions[$que_count]["answer"]; // obtener la respuesta correcta de la matriz
    $allOptions = count($option_list.children); // obtener todos los elementos de opción
    
    if($userAns == $correcAns){ // si la opción seleccionada por el usuario es igual a la

respuesta correcta de la matriz
$userScore += 1; // aumentar el valor de la puntuación en 1
$answer.classList.add("correct"); // agregar color verde a la opción seleccionada correctamente
$answer.insertAdjacentHTML("beforeend", $tickIconTag); // agregar ícono de marca a la opción seleccionada correctamente
echo "Respuesta correcta";
echo "Tus respuestas correctas = " . $userScore;
} else {
$answer.classList.add("incorrect"); // agregar color rojo a la opción seleccionada incorrectamente
$answer.insertAdjacentHTML("beforeend", $crossIconTag); // agregar ícono de cruz a la opción seleccionada incorrectamente
echo "Respuesta incorrecta";
    for($i=0; $i < $allOptions; $i++){
        if($option_list.children[$i].textContent == $correcAns){ // si hay una opción que coincide con una respuesta de la matriz
            $option_list.children[$i].setAttribute("class", "option correct"); // agregar color verde a la opción coincidente
            $option_list.children[$i].insertAdjacentHTML("beforeend", $tickIconTag); // agregar ícono de marca a la opción coincidente
            echo "Respuesta correcta seleccionada automáticamente.";
        }
    }
}

for($i=0; $i < $allOptions; $i++){
    $option_list.children[$i].classList.add("disabled"); // una vez que el usuario selecciona una opción, deshabilitar todas las opciones
}
$next_btn.classList.add("show"); // mostrar el botón siguiente si el usuario seleccionó alguna opción
}

function showResult(){
$info_box.classList.remove("activeInfo"); // ocultar cuadro de información
$quiz_box.classList.remove("activeQuiz"); // ocultar cuadro de cuestionario
$result_box.classList.add("activeResult"); // mostrar cuadro de resultados
$scoreText = $result_box.querySelector(".score_text");
if ($userScore > 3){ // si el usuario anotó más de 3
// creando una nueva etiqueta span y pasando el número de puntuación del usuario y el número total de preguntas
$scoreTag = '<span> y ¡Felicidades! 🎉, Tienes <p>'. $userScore .'</p> de <p>'. count($questions) .'</p></span>';
$scoreText.innerHTML = $scoreTag; // agregar nueva etiqueta span dentro de score_Text
}
else if($userScore > 1){ // si el usuario anotó más de 1
$scoreTag = '<span> y ¡Muy bien! 😎, Tienes <p>'. $userScore .'</p> de <p>'. count($questions) .'</p></span>';
$scoreText.innerHTML = $scoreTag;
}
else{ // si el usuario anotó menos de 1
$scoreTag = '<span> y Fallaste 😐, Tienes <p>'. $userScore .'</p> de <p>'. count($questions) .'</p></span>';
$scoreText.innerHTML = $scoreTag;
}
}

function startTimer($time){
$counter = setInterval(timer, 1000);
function timer(){
$timeCount.textContent = $time; // cambiar el valor de timeCount con el valor de tiempo
$time--; // decrementar el valor de tiempo
if($time < 9){ // si el temporizador es menor que 9
$addZero = $timeCount.textContent;
$timeCount.textContent = "0" . $addZero; // agregar un 0 antes del valor de tiempo
}
if($time < 0){ // si el temporizador es menor que 0
clearInterval($counter); // borrar contador
$timeText.textContent = "Se acabó el tiempo"; // cambiar el texto de tiempo a tiempo agotado
$allOptions = count($option_list.children); // obtener todos los elementos de opción
$correcAns = $questions[$que_count]["answer"]; // obtener la respuesta correcta de la matriz
for($i=0; $i < $allOptions; $i++){
if($option_list.children[$i].textContent == $correcAns){ // si hay una opción que coincide con una respuesta de la matriz
$option_list.children[$i].setAttribute("class", "option correct"); // agregar color verde a la opción coincidente
$option_list.children[$i].insertAdjacentHTML("beforeend", $tickIconTag); // agregar ícono de marca a la opción coincidente
echo "Tiempo agotado: se seleccionó automáticamente la respuesta correcta.";
}
}
for($i=0; $i < $allOptions; $i++){
$option_list.children[$i].classList.add("disabled"); // una vez que el usuario selecciona una opción, deshabilitar todas las opciones
}
$next_btn.classList.add("show"); // mostrar el botón siguiente si el usuario seleccionó alguna opción
}
}
}

function startTimerLine($time){
$counterLine = setInterval(timer, 39);
function timer(){
$time += 1; // aumentar el valor de tiempo en 1
$time_line.style.width = $time . "px"; // aumentar el ancho de time_line con px por el valor de tiempo
if($time > 549){ // si el valor de tiempo es mayor que 549
clearInterval($counterLine); // borrar counterLine
}
}
}

function queCounter($index){
// creando una nueva etiqueta span y pasando el número de pregunta y el número total de preguntas
$totalQueCounTag = '<span><p>'. $index .'</p> de <p>'. count($questions) .'</p> Preguntas</span>';
$bottom_ques_counter.innerHTML = $totalQueCounTag; // agregar nueva etiqueta span dentro de bottom_ques_counter
}
?>
