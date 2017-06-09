<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Horários - MusicSys</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/metisMenu.min.css" rel="stylesheet">
        <link href="css/sb-admin-2.css" rel="stylesheet">
        <link href="css/fullcalendar.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="css/estilo.css" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div id="wrapper">
            <?php include('./include/header.php'); ?>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Horários - Listar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Materiais
                                <?php
                                if ($_SESSION["usuario"]["admin"]) {
                                    ?>
                                    <a href="horarios_inserir.php" class="btn btn-success btn-panel-heading">Criar horário</a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="panel-body">
                                <div id="calendario"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/metisMenu.min.js"></script>
        <script src="js/sb-admin-2.js"></script>
        <script src="js/moment.min.js"></script>
        <script src="js/fullcalendar.min.js"></script>
        <script src="js/fullcalendar-pt-br.js"></script>
        <script>
            $(document).ready(function () {

                //CARREGA CALENDÁRIO E EVENTOS DO BANCO
                $('#calendario').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
//                    businessHours: {
//                        dow: [1, 2, 3, 4, 5],
//                        start: '08:00',
//                        end: '18:00'
//                    },
//                    eventDrop: function (event, delta) {
//                        var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
//                        $.ajax({
//                            url: 'function/horarios_editar.php',
//                            data: 'start=' + start + '&id=' + event.id,
//                            type: "POST",
//                            success: function (json) {
//                                alert("Updated Successfully");
//                            }
//                        });
//                    },
                    minTime: '08:00',
                    maxTime: '18:00',
                    //defaultView: 'agendaWeek',
//                    editable: true,
//                    droppable: true,
                    weekends: false,
                    allDaySlot: false,
                    eventDurationEditable: false,
                    height: 'auto',
                    events: 'function/horarios_listar.php',
                    slotDuration: '01:00:00',
                    eventColor: '#dd6777',
                    eventClick: function (event) {
                        window.location = "horarios_visualizar.php?id=" + event.id;
                        return false;
                    }
                });
            });

        </script>
    </body>
</html>