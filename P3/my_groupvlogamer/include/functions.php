<?php
/**
 * * Descripción: Controlador principal
 * *
 * * Descripción extensa: Iremos añadiendo cosas complejas en PHP.
 * *
 * * @author  EMC <atarom1987@gmail.com> 
 * * @copyright 2020 Edu
 * * @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
 * * @version 3
 * */


//Estas 2 instrucciones me aseguran que el usuario accede a través del WP. Y no directamente
if ( ! defined( 'WPINC' ) ) exit;

if ( ! defined( 'ABSPATH' ) ) exit;


// define ('SITE_ROOT', realpath(dirname(__FILE__)));




//Funcion instalación plugin. Crea tabla
function MP_CrearT_vlogamer($tabla){
    
    $MP_pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    $query="CREATE TABLE IF NOT EXISTS $tabla (person_id INT(11) NOT NULL AUTO_INCREMENT, nombre VARCHAR(100),  email VARCHAR(100),  foto_file VARCHAR(25), clienteMail VARCHAR(100),  PRIMARY KEY(person_id))";
    $consult = $MP_pdo->prepare($query);
    $consult->execute (array());
}


function MP_Register_Form_vlogamer($MP_user , $user_email)
{//formulario registro amigos de $user_email
    ?>
    <h1>Gestión de Usuarios </h1>

    <form class="fom_usuario" id=myFormAsync action="?action=my_datos_vlogamer&proceso=registrar" method="POST" enctype="multipart/form-data">
        <label for="clienteMail">Tu correo0o0o0o0o0o0o0os</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos33333</legend>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_user["userName"] ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_user["email"] ?>"
        placeholder="kiko@ic.es" />
        <br/>

        <label for="foto">Foto</label>
        <img id="img_foto"src="" width="100" height="60"></p>
        <br/>
        <input type="file" name="foto" id="foto" size="20" maxlength="25" value="<?php print $MP_user["foto"] ?>" />
        <br/>

        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>

    <script type="text/javascript" charset="utf-8" defer >
        // wp_enqueue_script('myScript');

        console.log("xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");

        //carga la imagen de file en el elemento src imagen
        function mostrarFoto(file, imagen) {
        var reader = new FileReader();
        reader.addEventListener("load", function () {
            imagen.src = reader.result;
        });
        reader.readAsDataURL(file);
        }

        // envia formulario
        async function registrarAsync(evento) {
        try {
            evento.preventDefault();
            console.log("registrarAsync 1");
            let url = evento.target.getAttribute("action");
            let data = new FormData(evento.target);
            let init = {
            url: url,
            method: "post",
            body: data,
            };
            console.log("registrarAsync 2", url, data);

            let request0 = new Request(url, init);

            const response = await fetch(request0);

            if (!response.ok) {
            throw Error(response.statusText);
            }
            const result = await response.text();
            alert("El registro se ha guardado correctamente.");
            console.log("Correcto devuelvo:", result);
        } catch (error) {
            console.log(error);
            alert("Hubo un error al guardar el registro. Inténtelo de nuevo.");
        }
        }

        //escuchamos evento selección nuevo fichero.
        function ready() {
        console.log("eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
        var fichero = document.querySelector("#foto");
        var imagen = document.querySelector("#img_foto");
        fichero.addEventListener("change", function (event) {
            mostrarFoto(this.files[0], imagen);
        });

        var myForm = document.querySelector("#myFormAsync");
        myForm.addEventListener("submit", function (event) {
            console.log("aded submit event");
            registrarAsync(event);
        });

        // if (document.forms.length > 0) {
        // document.forms[0].addEventListener("submit", function (event) {
        //     enviaForm(event);
        // })
        // }
        }
        ready();

    </script>

        
<?php
}

//CONTROLADOR
//Esta función realizará distintas acciones en función del valor del parámetro
//$_REQUEST['proceso'], o sea se activara al llamar a url semejantes a 
//https://host/wp-admin/admin-post.php?action=my_datos&proceso=r 

function MP_my_datos_vlogamer()
{ 
    global $user_ID , $user_email,$table;

    // wp_register_script('myScript', './actions.js' );
    
    $MP_pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    wp_get_current_user();
    if ('' == $user_ID) {
                //no user logged in
                exit;
    }
    
    
    
    if (!(isset($_REQUEST['action'])) or !(isset($_REQUEST['proceso']))) { print("Opciones no correctas $user_email"); exit;}

    get_header();
    echo '<div class="wrap">';

    switch ($_REQUEST['proceso']) {
        case "registro":
            $MP_user=null; //variable a rellenar cuando usamos modificar con este formulario
            MP_Register_Form_vlogamer($MP_user,$user_email);
            break;

        case "registrar":
            if (count($_REQUEST) < 3) {
                print ("No has rellenado el formulario correctamente");
                return;
            }

            $query = "INSERT INTO $table (nombre, email,clienteMail, foto_file) VALUES (?,?,?,?)";         
            $a=array($_REQUEST['userName'], $_REQUEST['email'],$_REQUEST['clienteMail'], $_FILES['foto']['name'] );
            //$pdo1 = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
            $consult = $MP_pdo->prepare($query);
            $a=$consult->execute($a);


    
            
            
            $fotoURL="";
            // $IMAGENES_USUARIOS = '/fotos/';
            $IMAGENES_USUARIOS = '/mnt/data/vhosts/casite-1253359.cloudaccess.net/httpdocs/fotos/';
            if(array_key_exists('foto', $_FILES) && $_POST['email']) {
                $fotoURL = $IMAGENES_USUARIOS.$_POST['userName']."_".$_FILES['foto']['name'];
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoURL))
                    { echo "foto subida con éxito";
            }}  

            var_dump($_FILES);
            echo $IMAGENES_USUARIOS.$_POST['userName']."_".$_FILES['foto']['name'];
            echo "---";
            echo $_FILES['foto']['tmp_name'];


            if (1>$a) {echo "InCorrecto $query";}
            else wp_redirect(admin_url( 'admin-post.php?action=my_datos_vlogamer&proceso=listar'));
            break;

        case "listar":
            //Listado amigos o de todos si se es administrador.
            $a=array();
            if (current_user_can('administrator')) {$query = "SELECT     * FROM       $table ";}
            else {$campo="clienteMail";
                $query = "SELECT     * FROM  $table      WHERE $campo =?";
                $a=array( $user_email);
            } 

            $consult = $MP_pdo->prepare($query);
            $a=$consult->execute($a);
            $rows=$consult->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($rows)) {/* Creamos un listado como una tabla HTML*/
                print '<div><table><th>';
                foreach ( array_keys($rows[0])as $key) {
                    echo "<td>", $key,"</td>";
                }
                print "</th>";
                foreach ($rows as $row) {
                    print "<tr>";
                    foreach ($row as $key => $val) {
                        echo "<td>", $val, "</td>";
                    }
                    print "</tr>";
                }
                print "</table></div>";
            }
            else{echo "No existen valores";}
            break;
        default:
            print "Opción no correcta";
        
    }
    echo "</div>";
    // get_footer ademas del pie de página carga el toolbar de administración de wordpres si es un 
    //usuario autentificado, por ello voy a borrar la acción cuando no es un administrador para que no aparezca.
    if (!current_user_can('administrator')) {

        // for the admin page
        remove_action('admin_footer', 'wp_admin_bar_render', 1000);
        // for the front-end
        remove_action('wp_footer', 'wp_admin_bar_render', 1000);
    }

    get_footer();
    }
//add_action('admin_post_nopriv_my_datos', 'my_datos');
//add_action('admin_post_my_datos', 'my_datos'); //no autentificados
?>
