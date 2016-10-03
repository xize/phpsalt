<?php
class Salt
{

    private static $generator;

    protected function __construct() {} //allow the class to be instanced only by it self.

    public function salt($password = "", $bits = 2048) {
        //converting to bytearray
        $bytes = unpack("C*", $password);
        //setting the bytes literally near each other presenting the seed
        $seed = "";
        foreach($bytes as $byte) {
            $seed += chr($byte);
        }
        //instancing the seed random generator with the seed.
        srand($seed); //<- do not remove, this is needed or else we cannot use it like a seed generator.
        //shuffle the bytes, by using & inside the method we use a memory pointer to the same instance, this will not return a fresh array but directly modifies it.
        shuffle($bytes);
        //add psuodo bytes to the salting procedure, this will be using the seed
        $this->addPsuodoUTFBytes($bytes, $bits);
        //shuffle all the bytes based on the seed.
        $this->shuffle($bytes);
        //add a ascii psuodo bytes
        $this->addPsuodoASCIIBytes($bytes);
        //shuffle the bytes again
        $this->shuffle($bytes);
        //add lenny faces
        $this->forceLennyFaces($bytes);
        //shuffle with the faces :D
        $this->shuffle($bytes);
        //creating the salt...
        $salt = "";
        foreach($bytes as $byte) {
            if(!is_int($byte)) {
                $salt .= $byte;
            } else {
                $salt .= chr($byte);
            }
        }
        //return the salted password :)
        return $salt;
    }

    private function shuffle(&$array) {
        for($index = 0; $index < count($array); $index++) {
            $array[$index] = $array[rand(1, (count($array) - 1))];
        }
    }

    private function addPsuodoUTFBytes(&$array, &$bits) {
        for($i = 0; $i < $bits; $i++) {
            $array[] = rand(0, 127); //generate something between 0 to 224 total 225 length, this support ascii to make it very hard to crackdown a password.
        }
    }

    private function addPsuodoASCIIBytes(&$array) {

        $bits = floor(count($array) / 40);

        for($i = 0; $i < $bits; $i++) {
            $array[] = rand(126, 224); //generate something between 0 to 224 total 225 length, this support ascii to make it very hard to crackdown a password.
        }
    }

    private function forceLennyFaces(&$array) {
        $bits = floor(count($array) / 60);

        $lennys = array(
            "( ͡° ͜ʖ ͡°)",
            "ᕦ( ͡° ͜ʖ ͡°)ᕤ",
            "( ͡☉ ͜ʖ ͡☉)",
            "(ง ͠° ͟ل͜ ͡°)ง",
            "( ͠° ͟ʖ ͡°)",
            "✺◟( ͡° ͜ʖ ͡°)◞✺",
            "︵‿︵(´ ͡༎ຶ ͜ʖ ͡༎ຶ `)︵‿︵",
            "凸( ͡° ͜ʖ ͡°)"
        );

        for($i = 0; $i < $bits; $i++) {
            $array[] = $lennys[rand(0, count($lennys)-1)]; //generate something between 0 to 224 total 225 length, this support ascii to make it very hard to crackdown a password.
        }
    }

    public static function getGenerator() {
        if(SELF::$generator instanceof Salt) {
            return SELF::$generator;
        }
        SELF::$generator = new Salt();
        return SELF::$generator;
    }

}