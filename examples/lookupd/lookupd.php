<?php
    use Aethalides\Systemd\Notify\NotifyFluent;

    require_once __DIR__.'/vendor/autoload.php';

    class Lookupd {

        const LISTEN_ADDRESS='127.0.0.1';

        const LISTEN_PORT=7777;

        const PULSE_SECS=10;

        private $notify=null;

        private $lsocket=null; // lsocket = listen socket

        private $conns=0;

        public function __construct() {

            $this->notify=new NotifyFluent;
        }

        public static function start() : int {

            $out=0;

            try {

                $objEngine=new static;

                $out=$objEngine->doMainloop();

            } catch(Exception $e) {

                fprintf(

                    STDERR,"%s: %s %s\n",

                    $_SERVER['argv'][0]??__FILE__,

                    get_class($e),

                    $e->getMessage()
                );

                $out=max(1,$e->getCode());
            }

            return $out;
        }

        public function __toString() : string {

            if(is_resource($this->lsocket)) {

                return sprintf(

                    'Listening on %s:%d; Served %d client(s)',

                    static::LISTEN_ADDRESS,

                    static::LISTEN_PORT,

                    $this->conns
                );
            }

            return "Offline";
        }

        private function getIncomingConnection() {

            $out=$write=$error=null;

            $read=array($this->lsocket);

            if(socket_select($read,$write,$error,0,0)) {

                $out=socket_accept($this->lsocket);

            }

            return $out;
        }

        private function setupSocket() {

            $socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

            if(is_resource($socket)) {

                socket_set_option($socket,SOL_SOCKET, SO_REUSEADDR, 1);

                if(socket_bind($socket,static::LISTEN_ADDRESS,static::LISTEN_PORT)) {

                    if(socket_listen($socket,1)) {

                        if(socket_set_nonblock($socket)) {

                            $this->lsocket=$socket;

                            return;
                        }
                    }
                }
            }

            $errno=socket_last_error();

            throw new Exception(

                sprintf(

                    'Problem setting up listening socket on %s:%d : Error #%d: %s',

                    static::LISTEN_ADDRESS,static::LISTEN_PORT,

                    $errno,socket_strerror($errno)
                )
            );
        }

        private function getSocketHost($connection) : ?string {

            $out=$address=null;

            if(socket_getpeername($connection,$address)) {

                $out=gethostbyaddr($address);
            }

            return $out;
        }

        private function doMainloop() : int {

            $out=0;

            $this->notify->open();

            $this->setupSocket();

            /* the initial notification
                provides additional optional information to Systemd */

            $this->notify
                ->setReady()
                ->setPid()
                ->setHeartbeat()
                ->setStatus((string) $this)
                ->send();

            $this->notify->clearVariables();

            $this->notify->setHeartbeat();

            $intNextHeartbeat=time()+static::PULSE_SECS;

            while(true) {

                if(time()>=$intNextHeartbeat) {

                    $this->notify->setStatus((string) $this)->send();

                    $intNextHeartbeat=time()+static::PULSE_SECS;
                }

                if($connection=$this->getIncomingConnection()) {

                    socket_write($connection,$this->getSocketHost($connection));

                    socket_close($connection);

                    ++$this->conns;
                }

                sleep(0.5);
            }

            return $out;
        }
    }
?>