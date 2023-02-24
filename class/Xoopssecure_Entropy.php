<?php

/**
 * DESCRIPTION
 *
 * (c) 2020 Gary Bell <gary@garybell.co.uk>
 *
 * @package password-validator
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XoopsModules\Xoopssecure;

/**
 *
 */
class Xoopssecure_Entropy
{

    /**
     * @var string $specialCharacters special characters to look for.
     */
    private static string $specialCharacters = ' !"#$%&\'()*+,-./:;<=>?@[\]^_{|}~';

    /**
     * @var string $lowercaseCharacters english characters in lower case.
     */
    private static string $lowercaseCharacters = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * @var string $uppercaseCharacters english characters in upper case.
     */
    private static string $uppercaseCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var string $numbers numeric characters to look for.
     */
    private static string $numbers = '0123456789';

    /**
     * The maximum number of times the same character can appear in the password
     *
     * @var integer $maxOccurrences
     */
    private static int $maxOccurrences = 2;


    /**
     * Html result
     * Get it all to html
     *
     * @param  string  $password the password to check.
     * @param  integer $min
     * @param  integer $medium
     * @param  integer $max
     * @return string
     */
    public static function resultHtml(string $password, int $min=100, int $medium=1000, int $max=20000000): string
    {
        // Get the base of the password (characters from different character sets used)
        $base = Entropy::getBase($password);
        // get the length of the password (characters used (only allows 2 of any single character)
        $length = Entropy::getLength($password);
        // get the entropy of the password
        $entropy = Entropy::getEntropy($password);
        // Guess time
        $guessCent         = Entropy::getGuess($password, $entropy, $min);
        $guessCentPos      = Entropy::getGuess($password, $entropy, (2 * $min));
        $guessMill         = Entropy::getGuess($password, $entropy, $medium);
        $guessMillPos      = Entropy::getGuess($password, $entropy, (2 * $medium));
        $guessCentMill     = Entropy::getGuess($password, $entropy, $max);
        $guessCentMillPos  = Entropy::getGuess($password, $entropy, (2 * $max));
        $guessCentText     = ($guessCent == 'Instantly') ? 'INSTANTLY' : '- Between '.$guessCentPos.' <strong>&</strong> '.$guessCent;
        $guessMillText     = ($guessMill == 'Instantly') ? 'INSTANTLY' : '- Between '.$guessMillPos.' <strong>&</strong> '.$guessMill;
        $guessCentMillText = ($guessCentMill == 'Instantly') ? 'INSTANTLY' : '- Between '.$guessCentMillPos.' <strong>&</strong> '.$guessCentMill;
        return '
                <p>
                    <span style="font-size:12px">
                        <strong>Password</strong>
                    </span>
                    <span style="font-size:10px"> : '.$password.' (Password to test)</span><br />
                <p><span style="font-size:12px"><strong>Base amount of characters</strong></span><span style="font-size:10px"> : '.$base.' (number of chars to choose from)</span><br />
                <span style="font-size:12px"><strong>Password lenght</strong></span><span style="font-size:10px"> : '.$length.' (lenght of password in chars)</span><br />
                <span style="font-size:12px"><strong>Password Entropy</strong></span><span style="font-size:10px"><strong> </strong>: '.$entropy.' (Strenght of password)</span><br />
                <br />
                <span style="font-size:12px"><strong>Estimated guesses with network latency, kill switches etc. a normal cipher brute-force attack by 1 user is 1000 G.P.S.</strong></span><br />
                <span style="font-size:10px">    <em>Possible the password is guessed when 50% of guess-pool is reached. So guess time is betwenn (0.5 * calculated time) - Calculated time.</em><br />
                <br />
                (<strong>Source</strong>    : <a href="https://palant.info/2023/01/30/password-strength-explained/">https://palant.info/2023/01/30/password-strength-explained/</a>)</span></p>
                <p><br />
                <span style="font-size:10px">------------------------------------------------------------------------------------------------------------------</span></p>

                <p><br />
                <span style="font-size:12px"><strong>Time to hack with brute force over slow protected http(s) network (est. '.$min.' guesses per second) : </strong></span><br />
                <span style="font-size:10px">'.$guessCentText.'</span><br />
                <span style="font-size:12px"><strong>Time to hack with brute force over fast network (est. '.$medium.' guesses per second) : </strong></span><br />
                <span style="font-size:10px">'.$guessMillText.'</span><br />
                <span style="font-size:12px"><strong>Time to hack stole password/password hash ('.$max.' guesses per second on a typical gamer pc): </strong></span><br />
                <span style="font-size:10px">'.$guessCentMillText.'</span></p>
            ';

    }//end resultHtml()


    /**
     * Get the base amount of characters from the characters used in the password.
     * This is the number of possible characters to pick from in the used character sets
     *   i.e. 26 for only lower case passwords
     *
     * @param  string $password
     * @return integer
     */
    public static function getBase(string $password): int
    {
        $characters = str_split($password);
        $base       = 0;
        $hasSpecial = false;
        $hasLower   = false;
        $hasUpper   = false;
        $hasDigits  = false;
        foreach ($characters as $character) {
            if (!$hasLower
                && str_contains(self::$lowercaseCharacters, $character)
            ) {
                $hasLower = true;
                $base    += strlen(self::$lowercaseCharacters);
            }

            if (!$hasUpper
                && str_contains(self::$uppercaseCharacters, $character)
            ) {
                $hasUpper = true;
                $base    += strlen(self::$uppercaseCharacters);
            }

            if (!$hasSpecial
                && str_contains(self::$specialCharacters, $character)
            ) {
                $hasSpecial = true;
                $base      += strlen(self::$specialCharacters);
            }

            if (!$hasDigits && str_contains(self::$numbers, $character)) {
                $hasDigits = true;
                $base     += strlen(self::$numbers);
            }

            if (!str_contains(self::$lowercaseCharacters, $character)
                && !str_contains(self::$uppercaseCharacters, $character)
                && !str_contains(self::$specialCharacters, $character)
                && !str_contains(self::$numbers, $character)
            ) {
                $base++;
            }
        }//end foreach

        return $base;

    }//end getBase()


    /**
     * Check the length of the password based on known rules
     *  Characters will only be counted a maximum of 2 times e.g. aaa has length 2
     *
     * @param  string $password
     * @return integer
     */
    public static function getLength(string $password): int
    {
        $usedCharacters = [];
        $characters     = str_split($password);
        $length         = 0;
        foreach ($characters as $character) {
            if (array_key_exists($character, $usedCharacters)
                && $usedCharacters[$character] < self::$maxOccurrences
            ) {
                $length++;
                $usedCharacters[$character]++;
            }

            if (!array_key_exists($character, $usedCharacters)) {
                $usedCharacters[$character] = 1;
                $length++;
            }
        }

        return $length;

    }//end getLength()


    /**
     * get the calculated entropy of the password based on the rules for excluding duplicate characters
     * If a password is in the banned list, entropy will be 0.
     *
     * @param  string  $password
     * @param  integer $decimalPlaces   (default 2)
     * @param  array   $bannedPasswords a custom list of passwords to disallow
     * @return float
     * @see    bannedPassords()
     */
    public static function getEntropy(string $password, int $decimalPlaces=2, array $bannedPasswords=[]): float
    {
        $banned = array_merge(self::bannedPasswords(), $bannedPasswords);
        if (in_array(strtolower($password), $banned)) {
            // these are so weak, we just want to outright ban them. Entropy will be 0 for anything in this list.
            return 0;
        }

        $base   = self::getBase($password);
        $length = self::getLength($password);
        return number_format(log($base ** $length), $decimalPlaces);

    }//end getEntropy()


    /**
     * A list of banned passwords i.e. used to return entropy 0 for being too common.
     * The list is made up of entries from the following sources, and made all lower case:
     *  - https://raw.githubusercontent.com/DavidWittman/wpxmlrpcbrute/master/wordlists/1000-most-common-passwords.txt
     *  - https://nordpass.com/most-common-passwords-list/
     *  - https://www.safetydetectives.com/blog/the-most-hacked-passwords-in-the-world/
     *  - https://www.forbes.com/sites/daveywinder/2019/12/14/ranked-the-worlds-100-worst-passwords/
     *
     * @return string[]
     */
    public static function bannedPasswords(): array
    {
        return array_merge(
            // passwords from https://raw.githubusercontent.com/DavidWittman/wpxmlrpcbrute/master/wordlists/1000-most-common-passwords.txt (taken 2021-01-21)
            [
                '123456',
                'password',
                '12345678',
                'qwerty',
                '123456789',
                '12345',
                '1234',
                '111111',
                '1234567',
                'dragon',
                '123123',
                'baseball',
                'abc123',
                'football',
                'monkey',
                'letmein',
                '696969',
                'shadow',
                'master',
                '666666',
                'qwertyuiop',
                '123321',
                'mustang',
                '1234567890',
                'michael',
                '654321',
                'pussy',
                'superman',
                '1qaz2wsx',
                '7777777',
                'fuckyou',
                '121212',
                '000000',
                'qazwsx',
                '123qwe',
                'killer',
                'trustno1',
                'jordan',
                'jennifer',
                'zxcvbnm',
                'asdfgh',
                'hunter',
                'buster',
                'soccer',
                'harley',
                'batman',
                'andrew',
                'tigger',
                'sunshine',
                'iloveyou',
                'fuckme',
                '2000',
                'charlie',
                'robert',
                'thomas',
                'hockey',
                'ranger',
                'daniel',
                'starwars',
                'klaster',
                '112233',
                'george',
                'asshole',
                'computer',
                'michelle',
                'jessica',
                'pepper',
                '1111',
                'zxcvbn',
                '555555',
                '11111111',
                '131313',
                'freedom',
                '777777',
                'pass',
                'fuck',
                'maggie',
                '159753',
                'aaaaaa',
                'ginger',
                'princess',
                'joshua',
                'cheese',
                'amanda',
                'summer',
                'love',
                'ashley',
                '6969',
                'nicole',
                'chelsea',
                'biteme',
                'matthew',
                'access',
                'yankees',
                '987654321',
                'dallas',
                'austin',
                'thunder',
                'taylor',
                'matrix',
                'william',
                'corvette',
                'hello',
                'martin',
                'heather',
                'secret',
                'fucker',
                'merlin',
                'diamond',
                '1234qwer',
                'gfhjkm',
                'hammer',
                'silver',
                '222222',
                '88888888',
                'anthony',
                'justin',
                'test',
                'bailey',
                'q1w2e3r4t5',
                'patrick',
                'internet',
                'scooter',
                'orange',
                '11111',
                'golfer',
                'cookie',
                'richard',
                'samantha',
                'bigdog',
                'guitar',
                'jackson',
                'whatever',
                'mickey',
                'chicken',
                'sparky',
                'snoopy',
                'maverick',
                'phoenix',
                'camaro',
                'sexy',
                'peanut',
                'morgan',
                'welcome',
                'falcon',
                'cowboy',
                'ferrari',
                'samsung',
                'andrea',
                'smokey',
                'steelers',
                'joseph',
                'mercedes',
                'dakota',
                'arsenal',
                'eagles',
                'melissa',
                'boomer',
                'booboo',
                'spider',
                'nascar',
                'monster',
                'tigers',
                'yellow',
                'xxxxxx',
                '123123123',
                'gateway',
                'marina',
                'diablo',
                'bulldog',
                'qwer1234',
                'compaq',
                'purple',
                'hardcore',
                'banana',
                'junior',
                'hannah',
                '123654',
                'porsche',
                'lakers',
                'iceman',
                'money',
                'cowboys',
                '987654',
                'london',
                'tennis',
                '999999',
                'ncc1701',
                'coffee',
                'scooby',
                '0000',
                'miller',
                'boston',
                'q1w2e3r4',
                'fuckoff',
                'brandon',
                'yamaha',
                'chester',
                'mother',
                'forever',
                'johnny',
                'edward',
                '333333',
                'oliver',
                'redsox',
                'player',
                'nikita',
                'knight',
                'fender',
                'barney',
                'midnight',
                'please',
                'brandy',
                'chicago',
                'badboy',
                'iwantu',
                'slayer',
                'rangers',
                'charles',
                'angel',
                'flower',
                'bigdaddy',
                'rabbit',
                'wizard',
                'bigdick',
                'jasper',
                'enter',
                'rachel',
                'chris',
                'steven',
                'winner',
                'adidas',
                'victoria',
                'natasha',
                '1q2w3e4r',
                'jasmine',
                'winter',
                'prince',
                'panties',
                'marine',
                'ghbdtn',
                'fishing',
                'cocacola',
                'casper',
                'james',
                '232323',
                'raiders',
                '888888',
                'marlboro',
                'gandalf',
                'asdfasdf',
                'crystal',
                '87654321',
                '12344321',
                'sexsex',
                'golden',
                'blowme',
                'bigtits',
                '8675309',
                'panther',
                'lauren',
                'angela',
                'bitch',
                'spanky',
                'thx1138',
                'angels',
                'madison',
                'winston',
                'shannon',
                'mike',
                'toyota',
                'blowjob',
                'jordan23',
                'canada',
                'sophie',
                'apples',
                'dick',
                'tiger',
                'razz',
                '123abc',
                'pokemon',
                'qazxsw',
                '55555',
                'qwaszx',
                'muffin',
                'johnson',
                'murphy',
                'cooper',
                'jonathan',
                'liverpoo',
                'david',
                'danielle',
                '159357',
                'jackie',
                '1990',
                '123456a',
                '789456',
                'turtle',
                'horny',
                'abcd1234',
                'scorpion',
                'qazwsxedc',
                '101010',
                'butter',
                'carlos',
                'password1',
                'dennis',
                'slipknot',
                'qwerty123',
                'booger',
                'asdf',
                '1991',
                'black',
                'startrek',
                '12341234',
                'cameron',
                'newyork',
                'rainbow',
                'nathan',
                'john',
                '1992',
                'rocket',
                'viking',
                'redskins',
                'butthead',
                'asdfghjkl',
                '1212',
                'sierra',
                'peaches',
                'gemini',
                'doctor',
                'wilson',
                'sandra',
                'helpme',
                'qwertyui',
                'victor',
                'florida',
                'dolphin',
                'pookie',
                'captain',
                'tucker',
                'blue',
                'liverpool',
                'theman',
                'bandit',
                'dolphins',
                'maddog',
                'packers',
                'jaguar',
                'lovers',
                'nicholas',
                'united',
                'tiffany',
                'maxwell',
                'zzzzzz',
                'nirvana',
                'jeremy',
                'suckit',
                'stupid',
                'porn',
                'monica',
                'elephant',
                'giants',
                'jackass',
                'hotdog',
                'rosebud',
                'success',
                'debbie',
                'mountain',
                '444444',
                'xxxxxxxx',
                'warrior',
                '1q2w3e4r5t',
                'q1w2e3',
                '123456q',
                'albert',
                'metallic',
                'lucky',
                'azerty',
                '7777',
                'shithead',
                'alex',
                'bond007',
                'alexis',
                '1111111',
                'samson',
                '5150',
                'willie',
                'scorpio',
                'bonnie',
                'gators',
                'benjamin',
                'voodoo',
                'driver',
                'dexter',
                '2112',
                'jason',
                'calvin',
                'freddy',
                '212121',
                'creative',
                '12345a',
                'sydney',
                'rush2112',
                '1989',
                'asdfghjk',
                'red123',
                'bubba',
                '4815162342',
                'passw0rd',
                'trouble',
                'gunner',
                'happy',
                'fucking',
                'gordon',
                'legend',
                'jessie',
                'stella',
                'qwert',
                'eminem',
                'arthur',
                'apple',
                'nissan',
                'bullshit',
                'bear',
                'america',
                '1qazxsw2',
                'nothing',
                'parker',
                '4444',
                'rebecca',
                'qweqwe',
                'garfield',
                '01012011',
                'beavis',
                '69696969',
                'jack',
                'asdasd',
                'december',
                '2222',
                '102030',
                '252525',
                '11223344',
                'magic',
                'apollo',
                'skippy',
                '315475',
                'girls',
                'kitten',
                'golf',
                'copper',
                'braves',
                'shelby',
                'godzilla',
                'beaver',
                'fred',
                'tomcat',
                'august',
                'buddy',
                'airborne',
                '1993',
                '1988',
                'lifehack',
                'qqqqqq',
                'brooklyn',
                'animal',
                'platinum',
                'phantom',
                'online',
                'xavier',
                'darkness',
                'blink182',
                'power',
                'fish',
                'green',
                '789456123',
                'voyager',
                'police',
                'travis',
                '12qwaszx',
                'heaven',
                'snowball',
                'lover',
                'abcdef',
                '00000',
                'pakistan',
                '007007',
                'walter',
                'playboy',
                'blazer',
                'cricket',
                'sniper',
                'hooters',
                'donkey',
                'willow',
                'loveme',
                'saturn',
                'therock',
                'redwings',
                'bigboy',
                'pumpkin',
                'trinity',
                'williams',
                'tits',
                'nintendo',
                'digital',
                'destiny',
                'topgun',
                'runner',
                'marvin',
                'guinness',
                'chance',
                'bubbles',
                'testing',
                'fire',
                'november',
                'minecraft',
                'asdf1234',
                'lasvegas',
                'sergey',
                'broncos',
                'cartman',
                'private',
                'celtic',
                'birdie',
                'little',
                'cassie',
                'babygirl',
                'donald',
                'beatles',
                '1313',
                'dickhead',
                'family',
                '12121212',
                'school',
                'louise',
                'gabriel',
                'eclipse',
                'fluffy',
                '147258369',
                'lol123',
                'explorer',
                'beer',
                'nelson',
                'flyers',
                'spencer',
                'scott',
                'lovely',
                'gibson',
                'doggie',
                'cherry',
                'andrey',
                'snickers',
                'buffalo',
                'pantera',
                'metallica',
                'member',
                'carter',
                'qwertyu',
                'peter',
                'alexande',
                'steve',
                'bronco',
                'paradise',
                'goober',
                '5555',
                'samuel',
                'montana',
                'mexico',
                'dreams',
                'michigan',
                'cock',
                'carolina',
                'yankee',
                'friends',
                'magnum',
                'surfer',
                'poopoo',
                'maximus',
                'genius',
                'cool',
                'vampire',
                'lacrosse',
                'asd123',
                'aaaa',
                'christin',
                'kimberly',
                'speedy',
                'sharon',
                'carmen',
                '111222',
                'kristina',
                'sammy',
                'racing',
                'ou812',
                'sabrina',
                'horses',
                '0987654321',
                'qwerty1',
                'pimpin',
                'baby',
                'stalker',
                'enigma',
                '147147',
                'star',
                'poohbear',
                'boobies',
                '147258',
                'simple',
                'bollocks',
                '12345q',
                'marcus',
                'brian',
                '1987',
                'qweasdzxc',
                'drowssap',
                'hahaha',
                'caroline',
                'barbara',
                'dave',
                'viper',
                'drummer',
                'action',
                'einstein',
                'bitches',
                'genesis',
                'hello1',
                'scotty',
                'friend',
                'forest',
                '010203',
                'hotrod',
                'google',
                'vanessa',
                'spitfire',
                'badger',
                'maryjane',
                'friday',
                'alaska',
                '1232323q',
                'tester',
                'jester',
                'jake',
                'champion',
                'billy',
                '147852',
                'rock',
                'hawaii',
                'badass',
                'chevy',
                '420420',
                'walker',
                'stephen',
                'eagle1',
                'bill',
                '1986',
                'october',
                'gregory',
                'svetlana',
                'pamela',
                '1984',
                'music',
                'shorty',
                'westside',
                'stanley',
                'diesel',
                'courtney',
                '242424',
                'kevin',
                'porno',
                'hitman',
                'boobs',
                'mark',
                '12345qwert',
                'reddog',
                'frank',
                'qwe123',
                'popcorn',
                'patricia',
                'aaaaaaaa',
                '1969',
                'teresa',
                'mozart',
                'buddha',
                'anderson',
                'paul',
                'melanie',
                'abcdefg',
                'security',
                'lucky1',
                'lizard',
                'denise',
                '3333',
                'a12345',
                '123789',
                'ruslan',
                'stargate',
                'simpsons',
                'scarface',
                'eagle',
                '123456789a',
                'thumper',
                'olivia',
                'naruto',
                '1234554321',
                'general',
                'cherokee',
                'a123456',
                'vincent',
                'Usuckballz1',
                'spooky',
                'qweasd',
                'cumshot',
                'free',
                'frankie',
                'douglas',
                'death',
                '1980',
                'loveyou',
                'kitty',
                'kelly',
                'veronica',
                'suzuki',
                'semperfi',
                'penguin',
                'mercury',
                'liberty',
                'spirit',
                'scotland',
                'natalie',
                'marley',
                'vikings',
                'system',
                'sucker',
                'king',
                'allison',
                'marshall',
                '1979',
                '098765',
                'qwerty12',
                'hummer',
                'adrian',
                '1985',
                'vfhbyf',
                'sandman',
                'rocky',
                'leslie',
                'antonio',
                '98765432',
                '4321',
                'softball',
                'passion',
                'mnbvcxz',
                'bastard',
                'passport',
                'horney',
                'rascal',
                'howard',
                'franklin',
                'bigred',
                'assman',
                'alexander',
                'homer',
                'redrum',
                'jupiter',
                'claudia',
                '55555555',
                '141414',
                'zaq12wsx',
                'shit',
                'patches',
                'nigger',
                'cunt',
                'raider',
                'infinity',
                'andre',
                '54321',
                'galore',
                'college',
                'russia',
                'kawasaki',
                'bishop',
                '77777777',
                'vladimir',
                'money1',
                'freeuser',
                'wildcats',
                'francis',
                'disney',
                'budlight',
                'brittany',
                '1994',
                '00000000',
                'sweet',
                'oksana',
                'honda',
                'domino',
                'bulldogs',
                'brutus',
                'swordfis',
                'norman',
                'monday',
                'jimmy',
                'ironman',
                'ford',
                'fantasy',
                '9999',
                '7654321',
                'hentai',
                'duncan',
                'cougar',
                '1977',
                'jeffrey',
                'house',
                'dancer',
                'brooke',
                'timothy',
                'super',
                'marines',
                'justice',
                'digger',
                'connor',
                'patriots',
                'karina',
                '202020',
                'molly',
                'everton',
                'tinker',
                'alicia',
                'rasdzv3',
                'poop',
                'pearljam',
                'stinky',
                'naughty',
                'colorado',
                '123123a',
                'water',
                'test123',
                'ncc1701d',
                'motorola',
                'ireland',
                'asdfg',
                'slut',
                'matt',
                'houston',
                'boogie',
                'zombie',
                'accord',
                'vision',
                'bradley',
                'reggie',
                'kermit',
                'froggy',
                'ducati',
                'avalon',
                '6666',
                '9379992',
                'sarah',
                'saints',
                'logitech',
                'chopper',
                '852456',
                'simpson',
                'madonna',
                'juventus',
                'claire',
                '159951',
                'zachary',
                'yfnfif',
                'wolverin',
                'warcraft',
                'hello123',
                'extreme',
                'penis',
                'peekaboo',
                'fireman',
                'eugene',
                'brenda',
                '123654789',
                'russell',
                'panthers',
                'georgia',
                'smith',
                'skyline',
                'jesus',
                'elizabet',
                'spiderma',
                'smooth',
                'pirate',
                'empire',
                'bullet',
                '8888',
                'virginia',
                'valentin',
                'psycho',
                'predator',
                'arizona',
                '134679',
                'mitchell',
                'alyssa',
                'vegeta',
                'titanic',
                'christ',
                'goblue',
                'fylhtq',
                'wolf',
                'mmmmmm',
                'kirill',
                'indian',
                'hiphop',
                'baxter',
                'awesome',
                'people',
                'danger',
                'roland',
                'mookie',
                '741852963',
                '1111111111',
                'dreamer',
                'bambam',
                'arnold',
                '1981',
                'skipper',
                'serega',
                'rolltide',
                'elvis',
                'changeme',
                'simon',
                '1q2w3e',
                'lovelove',
                'fktrcfylh',
                'denver',
                'tommy',
                'mine',
                'loverboy',
                'hobbes',
                'happy1',
                'alison',
                'nemesis',
                'chevelle',
                'cardinal',
                'burton',
                'wanker',
                'picard',
                '151515',
                'tweety',
                'michael1',
                '147852369',
                '12312',
                'xxxx',
                'windows',
                'turkey',
                '456789',
                '1974',
                'vfrcbv',
                'sublime',
                '1975',
                'galina',
                'bobby',
                'newport',
                'manutd',
                'daddy',
                'american',
                'alexandr',
                '1966',
                'victory',
                'rooster',
                'qqq111',
                'madmax',
                'electric',
                'bigcock',
                'a1b2c3',
                'wolfpack',
                'spring',
                'phpbb',
                'lalala',
                'suckme',
                'spiderman',
                'eric',
                'darkside',
                'classic',
                'raptor',
                '123456789q',
                'hendrix',
                '1982',
                'wombat',
                'avatar',
                'alpha',
                'zxc123',
                'crazy',
                'hard',
                'england',
                'brazil',
                '1978',
                '01011980',
                'wildcat',
                'polina',
                'freepass',
            ],
            // passwords from https://nordpass.com/most-common-passwords-list/ (taken 2021-01-21)
            // Only uses items which take > 1s to crack
            [
                'jobandtalent',
                'x4ivyga51f',
                'ohmnamah23',
                'bangbang123',
                'chatbooks',
                'jacket025',
                'sample123',
                'jakcgt333',
                'myspace1',
                'aaron431',
                'million2',
                'picture1',
                'qqww1122',
                'unknown',
                '25251325',
                'default',
                'yugioh',
                'omgpop',
                '686584',
                'a801016',
                '5201314',
                'basketball',
                'party',
                'evite',
                'senha',
                '6655321',
                'qwer123456',
                'chocolate',
                'babygirl1',
                'b123456',
                '123456b',
                'princess1',
                'iloveyou1',
                'zing',
            ],
            // https://www.safetydetectives.com/blog/the-most-hacked-passwords-in-the-world/
            // Deduplicated from other lists
            ['27653'],
            // https://www.forbes.com/sites/daveywinder/2019/12/14/ranked-the-worlds-100-worst-passwords/
            [
                '00000',
                '000000',
                '1234',
                '11111',
                '12345',
                '54321',
                '55555',
                '111111',
                '112233',
                '121212',
                '123123',
                '123321',
                '123456',
                '654321',
                '666666',
                '1234567',
                '12345678',
                '123456789',
                '987654321',
                '987654321',
                '1234567890',
                '12345678910',
                '1q2w3e4r5t',
                '1qaz2wsx3edc',
                'aa123456.',
                'abc123',
                'abcd1234',
                'amanda',
                'andrew',
                'animoto',
                'asdf',
                'asdfghjkl',
                'ashley',
                'babygirl',
                'bailey',
                'baseball',
                'basketball',
                'buster',
                'butterfly',
                'bvttest123',
                'charlie',
                'chegg123',
                'chocolate',
                'cookie',
                'daniel',
                'dragon',
                'dubsmash',
                'family',
                'fitness',
                'flower',
                'football',
                'fuckyou',
                'g_czechout',
                'ginger',
                'hannah',
                'hello',
                'hunter',
                'iloveyou',
                'jasmine',
                'jennifer',
                'jessica',
                'jordan',
                'joshua',
                'justin',
                'livetest',
                'lovely',
                'madison',
                'maggie',
                'maria',
                'matthew',
                'michael',
                'michelle',
                'monkey',
                'nicole',
                'password',
                'password',
                'password1',
                'pepper',
                'princess',
                'purple',
                'q1w2e3r4t5y6',
                'qwerty',
                'qwerty123',
                'qwertyuiop',
                'samantha',
                'shadow',
                'shopping',
                'soccer',
                'sophie',
                'summer',
                'sunshine',
                'superman',
                'taylor',
                'test',
                'test1',
                'thomas',
                'tigger',
                'whatever',
                'zinch',
                'zxcvbnm',
            ]
        );

    }//end bannedPasswords()


    /**
     * Time to hack password
     * - Estimated time before password is guessed with x guesses per second
     *
     * @param  string  $password
     * @param  float   $entropy  entropy of password
     * @param  integer $gPerSec
     * @return boolean|array|string
     */
    public static function getGuess(string $password, $entropy, $gPerSec=1000000): bool|array|string
    {
        return self::Sec2Time(floor(pow(2, $entropy) / $gPerSec), type: 'string');

    }//end getGuess()


    /**
     * Seconds to timeStamp
     * Return a timeStamp from total seconds
     *
     * @param  $time
     * @param  string $type of return array or string
     * @return boolean|array|string
     */
    public static function Sec2Time($time, string $type='array'): bool|array|string
    {
        if (is_numeric($time)) {
            $value  = [
                'years'   => 0,
                'days'    => 0,
                'hours'   => 0,
                'minutes' => 0,
                'seconds' => 0,
            ];
            $string = '';
            if ($time >= 31556926) {
                $value['years'] = floor($time / 31556926);
                $string        .= ($value['years'] > 0) ? $value['years'].' years : ' : '';
                $time           = ($time % 31556926);
            }

            if ($time >= 86400) {
                $value['days'] = floor($time / 86400);
                $string       .= ($value['days'] > 0) ? $value['days'].' days : ' : '';
                $time          = ($time % 86400);
            }

            if ($time >= 3600) {
                $value['hours'] = floor($time / 3600);
                $string        .= ($value['hours'] > 0) ? $value['hours'].' hours : ' : '';
                $time           = ($time % 3600);
            }

            if ($time >= 60) {
                $value['minutes'] = floor($time / 60);
                $string          .= ($value['minutes'] > 0) ? $value['minutes'].' minutes : ' : '';
                $time             = ($time % 60);
            }

            $value['seconds'] = floor($time);
            $string          .= ($value['seconds'] > 0) ? $value['seconds'].' seconds' : '';
            if ($type == 'array') {
                return $value;
            } else {
                if ($time > 0) {
                    return $string;
                } else {
                    return 'Instantly';
                }
            }
        } else {
            return false;
        }//end if

    }//end Sec2Time()


}//end class
