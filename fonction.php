 <?php
include("Net/SSH2.php") 
 
 /* Etablir une liaison avec public/private key */
        function connect_to($machine)
        {
                /* Connection sur le port 22 de $machine en utilisanr RSA */
                $connection=@ssh2_connect($machine, 22,
                        array("hostkey"=>"ssh-rsa"));
                if(!$connection)
                {
                        return false;
                }
 
                /* le fingerprint n'est pas teste, c'est voulu, il est juste affiche */
                $fingerprint=@ssh2_fingerprint($connection,
                        SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
 
                /* Utilisation de public/private key */
                if(@ssh2_auth_pubkey_file($connection, "user",
                "public_key", "private_key", "passphrase"))
                {
                        return array($connection,$fingerprint);
                } else {
                        return false;
                }
        }
 
 
        /* Executer une commande, retour les flux stderr et stdout de la commande */
        function ssh_command($connection, $cmd)
        {
                /* Exec commande */
                $stdout_stream=@ssh2_exec($connection, $cmd);
                if(!$stdout_stream)
                {
                        return false;
                }
 
                /* Extrait le flux stderr, a l'origine mixe dans stdout */
                $stderr_stream=@ssh2_fetch_stream($stdout_stream,
                        SSH2_STREAM_STDERR);
                if(!$stderr_stream)
                {
                        return false;
                }
 
                /* Les flux sont bloquant pour lire le contenu ensuite l'afficher */ 
                if(!@stream_set_blocking($stdout_stream, true))
                {
                        return false;
                }
                if(!@stream_set_blocking($stderr_stream, true))
                {
                        return;
                }
                return array($stdout_stream, $stderr_stream);
        }
        
?>