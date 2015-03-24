
<script>
$.ajaxSetup({'async': false});

// graphe profondeur
console.log("graphe profonfeur");

<?php
  require_once(__dir__."/../../admin/ConnexionBD.php");
  $connexion->exec("SET time_zone = '+00:00'");
  //donne toutes les profondeurs possibles
  $sql = "SELECT distinct nivProfond FROM capteur order by nivProfond ";
  $rqt = $connexion->query($sql);
  $profondeurs = [];
  //print_r($profondeurs);
  $names = [];
  $i=0;
  foreach ($rqt as $p) {
      array_push($names,"profondeur".$i);
      array_push($profondeurs,$p);
      $i +=1;
      //echo "console.log('codeBoucle');";
      echo "console.log('$names[0]');";
  }
?>
console.log(<?php echo json_encode($profondeurs); ?>);

console.log("chargement donnes profondeur");
var options = {series:[]};
<?php
  $dateDebut = isset($_POST['dateDebut']) ? strtotime(str_replace("/","-",$_POST['dateDebut'])) : strtotime(date('j/m/Y',mktime(0, 0, 0, date("m")  , date("d"), date("Y")-1))) ;
  $dateFin = isset($_POST['dateFin']) ? strtotime(str_replace("/","-",$_POST['dateFin'])) : strtotime(date('j/m/Y',mktime(0, 0, 0, date("m")  , date("d"), date("Y")))) ;

  //print_r($dateDebut);
  //print_r($dateFin);
  //test
  $test = [1,2,3,4];
  foreach ($profondeurs as $nivP) {
  ?>
  //console.log(<?php echo json_encode($nivP); ?>);

  var niveau = <?php echo json_encode($nivP['nivProfond']); ?> ;

    //var adr = 'includes/scripts/getDataChartProfondeur.php?profondeur=<?php echo $nivP ?>&start='+ Math.round(<?php echo $dateDebut*1000;?>) +'&callback=?';

    var adr = 'includes/scripts/getDataChartProfondeur.php?profondeur='+niveau+'&start='
            + Math.round(<?php echo $dateDebut*1000;?>) +'&end='
            + Math.round(<?php echo $dateFin*1000;?>) + '&callback=?';

    var indice = 0;
    var nom = "profondeur"+indice;

    console.log("boucle courbe ");

    $.getJSON(adr, function (data) {
      options.series.push({
        name : nom,
        data: data
      });
    });
    indice +=1;
  <?php
  }
?>
console.log("graphe 2");

var oppppt = {
                chart : {
                    type: 'line'
                },
                navigator : {
                    adaptToUpdatedData: false
                },
                scrollbar: {
                    liveRedraw: false
                },
                title: {
                  text: 'Graphe par profondeur'},
                rangeSelector : {
                    buttons: [{
                        type: 'hour',
                        count: 1,
                        text: '1h'
                    }, {
                        type: 'day',
                        count: 1,
                        text: '1d'
                    }, {
                        type: 'month',
                        count: 1,
                        text: '1m'
                    }, {
                        type: 'year',
                        count: 1,
                        text: '1y'
                    }, {
                        type: 'all',
                        text: 'All'
                    }],
                    inputEnabled: false, // it supports only days
                    selected : 4 // all
                },
                xAxis : {
                   title: {
                      text: 'Nom des sondes'
                    },
                },
                yAxis: {
                  title: {
                    text: 'Temperature (C°)'
                  }
                },
            };

    oppppt.series = options.series;
    /*oppppt.series =[{
            name: 'Profondeur 1',
            data: [[1,1.2],[2,1.1],[3,1.5],[4,1.3],[5,1]]
        }, {
            name: 'Profondeur 2',
            data: [[6,4.6],[7,4],[8,4.2],[9,4.4],[10,4.1]]
          }
        ];*/

$('#chartProfondeur').highcharts('StockChart', oppppt);

console.log("fin");

</script>

