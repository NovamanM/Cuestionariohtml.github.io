<?php
// seleccionando todos los elementos requeridos
$start_btn = '<button class="start_btn"></button>';
$info_box = '<div class="info_box"></div>';
$exit_btn = '<div class="buttons"><div class="quit"></div></div>';
$continue_btn = '<div class="buttons"><div class="restart"></div></div>';
$quiz_box = '<div class="quiz_box"></div>';
$result_box = '<div class="result_box"></div>';
$option_list = '<div class="option_list"></div>';
$time_line = '<header><div class="time_line"></div></header>';
$timeText = '<div class="timer"><div class="time_left_txt"></div></div>';
$timeCount = '<div class="timer"><div class="timer_sec"></div></div>';

// si se hace clic en el botón Iniciar prueba
$start_btn = 'onclick = "showInfoBox()"';

// si se hace clic en el botón Salir del cuestionario
$exit_btn = 'onclick = "hideInfoBox()"';

// si se hace clic en el botón continuar prueba
$continue_btn = 'onclick = "startQuiz()"';

$timeValue = 20;
$que_count = 0;
$que_numb = 1;
$userScore = 0;
$counter;
$counterLine;
$widthValue = 0;

$restart_quiz = '<div class="buttons"><div class="restart"></div></div>';
$quit_quiz = '<div class="buttons"><div class="quit"></div></div>';

// si se hace clic en el botón Reiniciar cuestionario
$restart_quiz = 'onclick = "restartQuiz()"';

// si se hace clic en el botón Salir del cuestionario
$quit_quiz = 'onclick = "quitQuiz()"';

$next_btn = '<footer><div class="next_btn"></div></footer>';
$bottom_ques_counter = '<footer><div class="total_que"></div></footer>';

// si se hace clic en el botón Next Que
$next_btn = 'onclick = "nextQuestion()"';

// obtener preguntas y opciones de la matriz
function showQuestions($index){
    global $questions;
    $que_text = '<div class="que_text"></div>';
    //crear una nueva etiqueta span y div para la pregunta y la opción y pasar el valor usando el índice de la matriz
    $que_tag = '<span>' . $questions[$index]['numb'] . ". " . $questions[$index]['question'] . '</span>';
    $option_tag = '<div class="option"><span>' . $questions[$index]['options'][0] . '</span></div>'
    . '<div class="option"><span>' . $questions[$index]['options'][1] . '</span></div>'
    . '<div class="option"><span>' . $questions[$index]['options'][2] . '</span></div>'
    . '<div class="option"><span>' . $questions[$index]['options'][3] . '</span></div>';
    echo $que_text; //agregar nueva etiqueta span dentro de que_tag
    echo $option_tag; //agregar nueva etiqueta div dentro de option_tag
}

// crear las nuevas etiquetas div para los iconos
$tickIconTag = '<div class="icon tick"><i class="fas fa-check"></i></div>';
$crossIconTag = '<div class="icon cross"><i class="fas fa-times"></i></div>';

// si el usuario hace clic en la opción
function optionSelected($answer){
    global $counter, $counterLine, $questions, $que_count, $userScore, $option_list, $next_btn;
    clearInterval($counter); //borrar contador
    clearInterval($counterLine); //borrar counterLine

    $userAns = $answer; //obtener la opción seleccionada por el usuario
    $correcAns = $questions[$que_count]['answer']; //obtener la respuesta correcta de la matriz
    $allOptions = count($option_list); //obtener todos los elementos de opción
    
    if($userAns == $correcAns){ //si la opción seleccionada por el usuario es igual a la respuesta correcta de la matriz
        $userScore += 1; //incrementar el valor de la puntuación en 1
        $answer.addClass("correct"); //agregar color verde a la opción seleccionada correctamente
        $answer.insertAfter($tickIconTag); //agregar ícono de marca a la opción seleccionada correctamente
        echo "Respuesta correcta";
        echo "Tus respuestas correctas = " . $userScore;
    }else{
        $answer.addClass("incorrect"); //agregar color rojo a la opción seleccionada incorrectamente
        $answer.insertAfter($crossIconTag); //agregar ícono de cruz a la opción seleccionada incorrectamente
        echo "Respuesta incorrecta";

        for($i=0; $i < $allOptions; $i++){
            if($option_list[$i] == $correcAns){ //si hay una opción que coincide con una respuesta de la matriz 
                $option_list[$i].addClass("correct"); //agregar color verde a la opción coincidente
                $option_list[$i].insertAfter($tickIconTag); //agregar ícono de marca a la opción coincidente
                echo "Auto seleccionó la respuesta correcta.";
            }
        }
    }

    for($i=0; $i < $allOptions; $i++){
        $option_list[$i].addClass("disabled"); //una vez que el usuario selecciona una opción, deshabilitar todas las opciones
    }
    $next_btn.addClass("show"); //mostrar el botón siguiente si el usuario seleccionó alguna opción
}

function showResult(){
    global $info_box, $quiz_box, $result_box, $userScore, $questions;
    $info_box.removeClass("activeInfo"); //ocultar cuadro de información
    $quiz_box.removeClass("activeQuiz"); //ocultar cuadro de cuestionario
    $result_box.addClass("activeResult"); //mostrar cuadro de resultados
    $scoreText = $result_box.find(".score_text");
    if ($userScore > 3){ // si el usuario anotó más de 3
        //crear una nueva etiqueta span y pasar el número de puntuación del usuario y el número total de preguntas
        $scoreTag = '<span> y  ¡Felicidades! 🎉, Tienes <p>'. $userScore .'</p> de <p>'. count($questions) .'</p></span>';
        $scoreText.html($scoreTag);  //agregar nueva etiqueta span dentro de score_Text
    }
    else if($userScore > 1){ // si el usuario anotó más de 1
        $scoreTag = '<span> y  ¡Muy bien! 😎, Tienes <p>'. $userScore .'</p> de  <p>'. count($questions) .'</p></span>';
        $scoreText.html($scoreTag);
    }
        else{ // si el usuario anotó menos de 1
        $scoreTag = '<span> y Fallaste 😐, Tienes  <p>'. $userScore .'</p> de  <p>'. count($questions) .'</p></span>';
        $scoreText.html($scoreTag);
    }
}

function startTimer($time){
    global $counter, $timeCount, $timeText, $option_list, $next_btn, $questions, $que_count;
    $counter = setInterval("timer()", 1000);
    function timer(){
        $timeCount.html($time); // cambiar el valor de timeCount con el valor de tiempo
        $time--; // decrementar el valor de tiempo
        if($time < 9){ // si el temporizador es menor que 9
            $addZero = $timeCount.html(); 
            $timeCount.html("0" . $addZero); // agregar un 0 antes del valor de tiempo
        }
        if($time < 0){ // si el temporizador es menor que 0
            clearInterval($counter); // borrar contador
            $timeText.html("Se acabó el tiempo"); // cambiar el texto de tiempo a tiempo agotado
            $allOptions = count($option_list); // obtener todos los elementos de opción
            $correcAns = $questions[$que_count]['answer']; // obtener la respuesta correcta de la matriz
            for($i=0; $i < $allOptions; $i++){
                if($option_list[$i].html() == $correcAns){ // si hay una opción que coincide con una respuesta de la matriz
                    $option_list[$i].addClass("option correct"); // agregar color verde a la opción coincidente
                    $option_list[$i].insertAfter($tickIconTag); // agregar ícono de marca a la opción coincidente
                    echo "Se acabó el tiempo: se seleccionó automáticamente la respuesta correcta.";
                }
            }
            for($i=0; $i < $allOptions; $i++){
                $option_list[$i].addClass("disabled"); // una vez que el usuario selecciona una opción, deshabilitar todas las opciones
            }
            $next_btn.addClass("show"); // mostrar el botón siguiente si el usuario seleccionó alguna opción
        }
    }
}

function startTimerLine($time){
    global $counterLine, $time_line;
    $counterLine = setInterval("timer()", 39);
    function timer(){
        $time += 1; // aumentar el valor de tiempo en 1
        $time_line.css("width", $time . "px"); // aumentar el ancho de time_line con px por el valor de tiempo
        if($time > 549){ // si el valor de tiempo es mayor que 549
            clearInterval($counterLine); // borrar counterLine
        }
    }
}

function queCounter($index){
    global $bottom_ques_counter, $questions;
    //crear una nueva etiqueta span y pasar el número de pregunta y pregunta total
    $totalQueCounTag = '<span><p>'. $index .'</p> of <p>'. count($questions) .'</p> Preguntas</span>';
    $bottom_ques_counter.html($totalQueCounTag);  // agregar nueva etiqueta span dentro de bottom_ques_counter
}
?>


