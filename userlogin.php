
<?php
	include "header.php";       
    require_once('WebsiteUser.php');
    session_start();
    if(isset($_SESSION['websiteUser'])){
        if($_SESSION['websiteUser']->isAuthenticated()){
            session_write_close();
            header('Location:mailing_list.php');
        }
    }
    $missingFields = false;
    if(isset($_POST['submit'])){
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($_POST['username'] == ""){
				$errorMessages['usernameError'] = "Please enter a user name.";
                $missingFields = true;
				
            } if ($_POST['password'] == "") {
				$errorMessages['passwordError'] = "Please enter a password.";
				$missingFields = true;
				
			} if(!$missingFields){
                $websiteUser = new WebsiteUser();
                if(!$websiteUser->hasDbError()){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $websiteUser->authenticate($username, $password);
                    if($websiteUser->isAuthenticated()){
                        $_SESSION['websiteUser'] = $websiteUser;
                        header('Location:mailing_list.php');
                    }
                }
			}
			
			else {
                if(isset($errorMessages['usernameError'])){
                        echo '<span style=\'color:red\'>' . $errorMessages['usernameError'] . '</span>';
                }
				if(isset($errorMessages['passwordError'])){
                        echo '<span style=\'color:red\'>' . $errorMessages['passwordError'] . '</span>';
                }
            }
        }
    }
?>

<!DOCTYPE html>

        <!-- MESSAGES -->
        <?php
            //Authentication failed
            if(isset($websiteUser)){
                if(!$websiteUser->isAuthenticated()){
                    echo '<h3 style="color:red;">Login failed. Please try again.</h3>';
                }
            }
        ?>
        
        <form name="login" id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" id="username"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Login"></td>
                <td><input type="reset" name="reset" id="reset" value="Reset"></td>
            </tr>
        </table>
            <?php echo '<p>Session ID: ' . session_id() . '</p>';?>
        </form>
    
<?php
include "footer.php";                 
?>
