<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $jsonFilePath1 = storage_path('json_files/template1.json');
        $jsonContent1 = file_get_contents($jsonFilePath1);
        $jsonFilePath2 = storage_path('json_files/template2.json');
        $jsonContent2 = file_get_contents($jsonFilePath2);
        $jsonFilePath3 = storage_path('json_files/template3.json');
        $jsonContent3 = file_get_contents($jsonFilePath3);

        DB::table('roles')->insert([
            ['role_name' => 'Super Admin'],
            ['role_name' => 'Admin'],
            ['role_name' => 'User'],
        ]);
        DB::table('users')->insert([
            [
                'first_name' => 'Super1',
                'last_name' => 'Admin',
                'email' => 'superadmin1@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '2003-10-13',
                'gender' => 'male',
                'role_id' => DB::table('roles')->where('role_name', 'Super Admin')->first()->id,
            ],
            [
                'first_name' => 'Super2',
                'last_name' => 'Admin',
                'email' => 'superadmin2@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '1988-02-22',
                'gender' => 'female',
                'role_id' => DB::table('roles')->where('role_name', 'Super Admin')->first()->id,
            ],
            [
                'first_name' => 'Admin1',
                'last_name' => 'Admin',
                'email' => 'admin1@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '1998-03-12',
                'gender' => 'male',
                'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
            ],
            [
                'first_name' => 'Admin2',
                'last_name' => 'Admin',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '1978-05-03',
                'gender' => 'female',
                'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
            ],
            [
                'first_name' => 'Admin3',
                'last_name' => 'Admin',
                'email' => 'admin3@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '1990-07-21',
                'gender' => null,
                'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '2001-12-12',
                'gender' => 'male',
                'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '2000-08-09',
                'gender' => 'female',
                'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
            ],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'user3@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'birthdate' => '2000-08-09',
                'gender' => null,
                'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
            ],
        ]);
        DB::table('participant_statuses')->insert([
            ['status' => 'Accepted'],
            ['status' => 'Declined'],
            ['status' => 'Pending'],
        ]);
        DB::table('statuses')->insert([
            ['status' => 'Active'],
            ['status' => 'Inactive'],
        ]);

        DB::table('question_types')->insert([
            ['type' => 'Essay'],
            ['type' => 'Radio'],
        ]);

        DB::table('cert_templates')->insert([
            [
                'template_name' => 'sample_1',
                'design' => $jsonContent1,
                'path' => 'storage/images/certificates/cert_templates/template1.png',
                'status_id' => '1',
                'created_at' => now(),
            ],
            [
                'template_name' => 'sample_2',
                'design' => $jsonContent2,
                'path' => 'storage/images/certificates/cert_templates/template2.png',
                'status_id' => '1',
                'created_at' => now(),
            ],
            [
                'template_name' => 'sample_3',
                'design' => $jsonContent3,
                'path' => 'storage/images/certificates/cert_templates/template3.png',
                'status_id' => '1',
                'created_at' => now(),
            ],
        ]);

        DB::table('regions')->insert([
            ['psgcCode' => '010000000', 'regDesc' => 'REGION I (ILOCOS REGION)', 'regCode' => '01'],
            ['psgcCode' => '020000000', 'regDesc' => 'REGION II (CAGAYAN VALLEY)', 'regCode' => '02'],
            ['psgcCode' => '030000000', 'regDesc' => 'REGION III (CENTRAL LUZON)', 'regCode' => '03'],
            ['psgcCode' => '040000000', 'regDesc' => 'REGION IV-A (CALABARZON)', 'regCode' => '04'],
            ['psgcCode' => '170000000', 'regDesc' => 'REGION IV-B (MIMAROPA)', 'regCode' => '17'],
            ['psgcCode' => '050000000', 'regDesc' => 'REGION V (BICOL REGION)', 'regCode' => '05'],
            ['psgcCode' => '060000000', 'regDesc' => 'REGION VI (WESTERN VISAYAS)', 'regCode' => '06'],
            ['psgcCode' => '070000000', 'regDesc' => 'REGION VII (CENTRAL VISAYAS)', 'regCode' => '07'],
            ['psgcCode' => '080000000', 'regDesc' => 'REGION VIII (EASTERN VISAYAS)', 'regCode' => '08'],
            ['psgcCode' => '090000000', 'regDesc' => 'REGION IX (ZAMBOANGA PENINSULA)', 'regCode' => '09'],
            ['psgcCode' => '100000000', 'regDesc' => 'REGION X (NORTHERN MINDANAO)', 'regCode' => '10'],
            ['psgcCode' => '110000000', 'regDesc' => 'REGION XI (DAVAO REGION)', 'regCode' => '11'],
            ['psgcCode' => '120000000', 'regDesc' => 'REGION XII (SOCCSKSARGEN)', 'regCode' => '12'],
            ['psgcCode' => '130000000', 'regDesc' => 'NATIONAL CAPITAL REGION (NCR)', 'regCode' => '13'],
            ['psgcCode' => '140000000', 'regDesc' => 'CORDILLERA ADMINISTRATIVE REGION (CAR)', 'regCode' => '14'],
            ['psgcCode' => '150000000', 'regDesc' => 'AUTONOMOUS REGION IN MUSLIM MINDANAO (ARMM)', 'regCode' => '15'],
            ['psgcCode' => '160000000', 'regDesc' => 'REGION XIII (Caraga)', 'regCode' => '16'],
        ]);

        DB::table('provinces')->insert([
            ['psgcCode' => '012800000', 'provDesc' => 'ILOCOS NORTE', 'regCode' => '01', 'provCode' => '0128'],
            ['psgcCode' => '012900000', 'provDesc' => 'ILOCOS SUR', 'regCode' => '01', 'provCode' => '0129'],
            ['psgcCode' => '013300000', 'provDesc' => 'LA UNION', 'regCode' => '01', 'provCode' => '0133'],
            ['psgcCode' => '015500000', 'provDesc' => 'PANGASINAN', 'regCode' => '01', 'provCode' => '0155'],
            ['psgcCode' => '020900000', 'provDesc' => 'BATANES', 'regCode' => '02', 'provCode' => '0209'],
            ['psgcCode' => '021500000', 'provDesc' => 'CAGAYAN', 'regCode' => '02', 'provCode' => '0215'],
            ['psgcCode' => '023100000', 'provDesc' => 'ISABELA', 'regCode' => '02', 'provCode' => '0231'],
            ['psgcCode' => '025000000', 'provDesc' => 'NUEVA VIZCAYA', 'regCode' => '02', 'provCode' => '0250'],
            ['psgcCode' => '025700000', 'provDesc' => 'QUIRINO', 'regCode' => '02', 'provCode' => '0257'],
            ['psgcCode' => '030800000', 'provDesc' => 'BATAAN', 'regCode' => '03', 'provCode' => '0308'],
            ['psgcCode' => '031400000', 'provDesc' => 'BULACAN', 'regCode' => '03', 'provCode' => '0314'],
            ['psgcCode' => '034900000', 'provDesc' => 'NUEVA ECIJA', 'regCode' => '03', 'provCode' => '0349'],
            ['psgcCode' => '035400000', 'provDesc' => 'PAMPANGA', 'regCode' => '03', 'provCode' => '0354'],
            ['psgcCode' => '036900000', 'provDesc' => 'TARLAC', 'regCode' => '03', 'provCode' => '0369'],
            ['psgcCode' => '037100000', 'provDesc' => 'ZAMBALES', 'regCode' => '03', 'provCode' => '0371'],
            ['psgcCode' => '037700000', 'provDesc' => 'AURORA', 'regCode' => '03', 'provCode' => '0377'],
            ['psgcCode' => '041000000', 'provDesc' => 'BATANGAS', 'regCode' => '04', 'provCode' => '0410'],
            ['psgcCode' => '042100000', 'provDesc' => 'CAVITE', 'regCode' => '04', 'provCode' => '0421'],
            ['psgcCode' => '043400000', 'provDesc' => 'LAGUNA', 'regCode' => '04', 'provCode' => '0434'],
            ['psgcCode' => '045600000', 'provDesc' => 'QUEZON', 'regCode' => '04', 'provCode' => '0456'],
            ['psgcCode' => '045800000', 'provDesc' => 'RIZAL', 'regCode' => '04', 'provCode' => '0458'],
            ['psgcCode' => '174000000', 'provDesc' => 'MARINDUQUE', 'regCode' => '17', 'provCode' => '1740'],
            ['psgcCode' => '175100000', 'provDesc' => 'OCCIDENTAL MINDORO', 'regCode' => '17', 'provCode' => '1751'],
            ['psgcCode' => '175200000', 'provDesc' => 'ORIENTAL MINDORO', 'regCode' => '17', 'provCode' => '1752'],
            ['psgcCode' => '175300000', 'provDesc' => 'PALAWAN', 'regCode' => '17', 'provCode' => '1753'],
            ['psgcCode' => '175900000', 'provDesc' => 'ROMBLON', 'regCode' => '17', 'provCode' => '1759'],
            ['psgcCode' => '050500000', 'provDesc' => 'ALBAY', 'regCode' => '05', 'provCode' => '0505'],
            ['psgcCode' => '051600000', 'provDesc' => 'CAMARINES NORTE', 'regCode' => '05', 'provCode' => '0516'],
            ['psgcCode' => '051700000', 'provDesc' => 'CAMARINES SUR', 'regCode' => '05', 'provCode' => '0517'],
            ['psgcCode' => '052000000', 'provDesc' => 'CATANDUANES', 'regCode' => '05', 'provCode' => '0520'],
            ['psgcCode' => '054100000', 'provDesc' => 'MASBATE', 'regCode' => '05', 'provCode' => '0541'],
            ['psgcCode' => '056200000', 'provDesc' => 'SORSOGON', 'regCode' => '05', 'provCode' => '0562'],
            ['psgcCode' => '060400000', 'provDesc' => 'AKLAN', 'regCode' => '06', 'provCode' => '0604'],
            ['psgcCode' => '060600000', 'provDesc' => 'ANTIQUE', 'regCode' => '06', 'provCode' => '0606'],
            ['psgcCode' => '061900000', 'provDesc' => 'CAPIZ', 'regCode' => '06', 'provCode' => '0619'],
            ['psgcCode' => '063000000', 'provDesc' => 'ILOILO', 'regCode' => '06', 'provCode' => '0630'],
            ['psgcCode' => '064500000', 'provDesc' => 'NEGROS OCCIDENTAL', 'regCode' => '06', 'provCode' => '0645'],
            ['psgcCode' => '067900000', 'provDesc' => 'GUIMARAS', 'regCode' => '06', 'provCode' => '0679'],
            ['psgcCode' => '071200000', 'provDesc' => 'BOHOL', 'regCode' => '07', 'provCode' => '0712'],
            ['psgcCode' => '072200000', 'provDesc' => 'CEBU', 'regCode' => '07', 'provCode' => '0722'],
            ['psgcCode' => '074600000', 'provDesc' => 'NEGROS ORIENTAL', 'regCode' => '07', 'provCode' => '0746'],
            ['psgcCode' => '076100000', 'provDesc' => 'SIQUIJOR', 'regCode' => '07', 'provCode' => '0761'],
            ['psgcCode' => '082600000', 'provDesc' => 'EASTERN SAMAR', 'regCode' => '08', 'provCode' => '0826'],
            ['psgcCode' => '083700000', 'provDesc' => 'LEYTE', 'regCode' => '08', 'provCode' => '0837'],
            ['psgcCode' => '084800000', 'provDesc' => 'NORTHERN SAMAR', 'regCode' => '08', 'provCode' => '0848'],
            ['psgcCode' => '086000000', 'provDesc' => 'SAMAR (WESTERN SAMAR)', 'regCode' => '08', 'provCode' => '0860'],
            ['psgcCode' => '086400000', 'provDesc' => 'SOUTHERN LEYTE', 'regCode' => '08', 'provCode' => '0864'],
            ['psgcCode' => '087800000', 'provDesc' => 'BILIRAN', 'regCode' => '08', 'provCode' => '0878'],
            ['psgcCode' => '097200000', 'provDesc' => 'ZAMBOANGA DEL NORTE', 'regCode' => '09', 'provCode' => '0972'],
            ['psgcCode' => '097300000', 'provDesc' => 'ZAMBOANGA DEL SUR', 'regCode' => '09', 'provCode' => '0973'],
            ['psgcCode' => '098300000', 'provDesc' => 'ZAMBOANGA SIBUGAY', 'regCode' => '09', 'provCode' => '0983'],
            ['psgcCode' => '099700000', 'provDesc' => 'CITY OF ISABELA', 'regCode' => '09', 'provCode' => '0997'],
            ['psgcCode' => '101300000', 'provDesc' => 'BUKIDNON', 'regCode' => '10', 'provCode' => '1013'],
            ['psgcCode' => '101800000', 'provDesc' => 'CAMIGUIN', 'regCode' => '10', 'provCode' => '1018'],
            ['psgcCode' => '103500000', 'provDesc' => 'LANAO DEL NORTE', 'regCode' => '10', 'provCode' => '1035'],
            ['psgcCode' => '104200000', 'provDesc' => 'MISAMIS OCCIDENTAL', 'regCode' => '10', 'provCode' => '1042'],
            ['psgcCode' => '104300000', 'provDesc' => 'MISAMIS ORIENTAL', 'regCode' => '10', 'provCode' => '1043'],
            ['psgcCode' => '112300000', 'provDesc' => 'DAVAO DEL NORTE', 'regCode' => '11', 'provCode' => '1123'],
            ['psgcCode' => '112400000', 'provDesc' => 'DAVAO DEL SUR', 'regCode' => '11', 'provCode' => '1124'],
            ['psgcCode' => '112500000', 'provDesc' => 'DAVAO ORIENTAL', 'regCode' => '11', 'provCode' => '1125'],
            ['psgcCode' => '118200000', 'provDesc' => 'COMPOSTELA VALLEY', 'regCode' => '11', 'provCode' => '1182'],
            ['psgcCode' => '118600000', 'provDesc' => 'DAVAO OCCIDENTAL', 'regCode' => '11', 'provCode' => '1186'],
            ['psgcCode' => '124700000', 'provDesc' => 'COTABATO (NORTH COTABATO)', 'regCode' => '12', 'provCode' => '1247'],
            ['psgcCode' => '126300000', 'provDesc' => 'SOUTH COTABATO', 'regCode' => '12', 'provCode' => '1263'],
            ['psgcCode' => '126500000', 'provDesc' => 'SULTAN KUDARAT', 'regCode' => '12', 'provCode' => '1265'],
            ['psgcCode' => '128000000', 'provDesc' => 'SARANGANI', 'regCode' => '12', 'provCode' => '1280'],
            ['psgcCode' => '129800000', 'provDesc' => 'COTABATO CITY', 'regCode' => '12', 'provCode' => '1298'],
            ['psgcCode' => '133900000', 'provDesc' => 'NCR, CITY OF MANILA, FIRST DISTRICT', 'regCode' => '13', 'provCode' => '1339'],
            ['psgcCode' => '133900000', 'provDesc' => 'CITY OF MANILA', 'regCode' => '13', 'provCode' => '1339'],
            ['psgcCode' => '137400000', 'provDesc' => 'NCR, SECOND DISTRICT', 'regCode' => '13', 'provCode' => '1374'],
            ['psgcCode' => '137500000', 'provDesc' => 'NCR, THIRD DISTRICT', 'regCode' => '13', 'provCode' => '1375'],
            ['psgcCode' => '137600000', 'provDesc' => 'NCR, FOURTH DISTRICT', 'regCode' => '13', 'provCode' => '1376'],
            ['psgcCode' => '140100000', 'provDesc' => 'ABRA', 'regCode' => '14', 'provCode' => '1401'],
            ['psgcCode' => '141100000', 'provDesc' => 'BENGUET', 'regCode' => '14', 'provCode' => '1411'],
            ['psgcCode' => '142700000', 'provDesc' => 'IFUGAO', 'regCode' => '14', 'provCode' => '1427'],
            ['psgcCode' => '143200000', 'provDesc' => 'KALINGA', 'regCode' => '14', 'provCode' => '1432'],
            ['psgcCode' => '144400000', 'provDesc' => 'MOUNTAIN PROVINCE', 'regCode' => '14', 'provCode' => '1444'],
            ['psgcCode' => '148100000', 'provDesc' => 'APAYAO', 'regCode' => '14', 'provCode' => '1481'],
            ['psgcCode' => '150700000', 'provDesc' => 'BASILAN', 'regCode' => '15', 'provCode' => '1507'],
            ['psgcCode' => '153600000', 'provDesc' => 'LANAO DEL SUR', 'regCode' => '15', 'provCode' => '1536'],
            ['psgcCode' => '153800000', 'provDesc' => 'MAGUINDANAO', 'regCode' => '15', 'provCode' => '1538'],
            ['psgcCode' => '156600000', 'provDesc' => 'SULU', 'regCode' => '15', 'provCode' => '1566'],
            ['psgcCode' => '157000000', 'provDesc' => 'TAWI-TAWI', 'regCode' => '15', 'provCode' => '1570'],
            ['psgcCode' => '160200000', 'provDesc' => 'AGUSAN DEL NORTE', 'regCode' => '16', 'provCode' => '1602'],
            ['psgcCode' => '160300000', 'provDesc' => 'AGUSAN DEL SUR', 'regCode' => '16', 'provCode' => '1603'],
            ['psgcCode' => '166700000', 'provDesc' => 'SURIGAO DEL NORTE', 'regCode' => '16', 'provCode' => '1667'],
            ['psgcCode' => '166800000', 'provDesc' => 'SURIGAO DEL SUR', 'regCode' => '16', 'provCode' => '1668'],
            ['psgcCode' => '168500000', 'provDesc' => 'DINAGAT ISLANDS', 'regCode' => '16', 'provCode' => '1685'],
        ]);
        
        
        

        DB::table('countries')->insert([
            ['countrycode' => 'AFG', 'countryname' => 'Afghanistan', 'code' => 'AF'],
            ['countrycode' => 'ALA', 'countryname' => 'Åland', 'code' => 'AX'],
            ['countrycode' => 'ALB', 'countryname' => 'Albania', 'code' => 'AL'],
            ['countrycode' => 'DZA', 'countryname' => 'Algeria', 'code' => 'DZ'],
            ['countrycode' => 'ASM', 'countryname' => 'American Samoa', 'code' => 'AS'],
            ['countrycode' => 'AND', 'countryname' => 'Andorra', 'code' => 'AD'],
            ['countrycode' => 'AGO', 'countryname' => 'Angola', 'code' => 'AO'],
            ['countrycode' => 'AIA', 'countryname' => 'Anguilla', 'code' => 'AI'],
            ['countrycode' => 'ATA', 'countryname' => 'Antarctica', 'code' => 'AQ'],
            ['countrycode' => 'ATG', 'countryname' => 'Antigua and Barbuda', 'code' => 'AG'],
            ['countrycode' => 'ARG', 'countryname' => 'Argentina', 'code' => 'AR'],
            ['countrycode' => 'ARM', 'countryname' => 'Armenia', 'code' => 'AM'],
            ['countrycode' => 'ABW', 'countryname' => 'Aruba', 'code' => 'AW'],
            ['countrycode' => 'AUS', 'countryname' => 'Australia', 'code' => 'AU'],
            ['countrycode' => 'AUT', 'countryname' => 'Austria', 'code' => 'AT'],
            ['countrycode' => 'AZE', 'countryname' => 'Azerbaijan', 'code' => 'AZ'],
            ['countrycode' => 'BHS', 'countryname' => 'Bahamas', 'code' => 'BS'],
            ['countrycode' => 'BHR', 'countryname' => 'Bahrain', 'code' => 'BH'],
            ['countrycode' => 'BGD', 'countryname' => 'Bangladesh', 'code' => 'BD'],
            ['countrycode' => 'BRB', 'countryname' => 'Barbados', 'code' => 'BB'],
            ['countrycode' => 'BLR', 'countryname' => 'Belarus', 'code' => 'BY'],
            ['countrycode' => 'BEL', 'countryname' => 'Belgium', 'code' => 'BE'],
            ['countrycode' => 'BLZ', 'countryname' => 'Belize', 'code' => 'BZ'],
            ['countrycode' => 'BEN', 'countryname' => 'Benin', 'code' => 'BJ'],
            ['countrycode' => 'BMU', 'countryname' => 'Bermuda', 'code' => 'BM'],
            ['countrycode' => 'BTN', 'countryname' => 'Bhutan', 'code' => 'BT'],
            ['countrycode' => 'BOL', 'countryname' => 'Bolivia', 'code' => 'BO'],
            ['countrycode' => 'BES', 'countryname' => 'Bonaire', 'code' => 'BQ'],
            ['countrycode' => 'BIH', 'countryname' => 'Bosnia and Herzegovina', 'code' => 'BA'],
            ['countrycode' => 'BWA', 'countryname' => 'Botswana', 'code' => 'BW'],
            ['countrycode' => 'BVT', 'countryname' => 'Bouvet Island', 'code' => 'BV'],
            ['countrycode' => 'BRA', 'countryname' => 'Brazil', 'code' => 'BR'],
            ['countrycode' => 'IOT', 'countryname' => 'British Indian Ocean Territory', 'code' => 'IO'],
            ['countrycode' => 'VGB', 'countryname' => 'British Virgin Islands', 'code' => 'VG'],
            ['countrycode' => 'BRN', 'countryname' => 'Brunei', 'code' => 'BN'],
            ['countrycode' => 'BGR', 'countryname' => 'Bulgaria', 'code' => 'BG'],
            ['countrycode' => 'BFA', 'countryname' => 'Burkina Faso', 'code' => 'BF'],
            ['countrycode' => 'BDI', 'countryname' => 'Burundi', 'code' => 'BI'],
            ['countrycode' => 'KHM', 'countryname' => 'Cambodia', 'code' => 'KH'],
            ['countrycode' => 'CMR', 'countryname' => 'Cameroon', 'code' => 'CM'],
            ['countrycode' => 'CAN', 'countryname' => 'Canada', 'code' => 'CA'],
            ['countrycode' => 'CPV', 'countryname' => 'Cape Verde', 'code' => 'CV'],
            ['countrycode' => 'CYM', 'countryname' => 'Cayman Islands', 'code' => 'KY'],
            ['countrycode' => 'CAF', 'countryname' => 'Central African Republic', 'code' => 'CF'],
            ['countrycode' => 'TCD', 'countryname' => 'Chad', 'code' => 'TD'],
            ['countrycode' => 'CHL', 'countryname' => 'Chile', 'code' => 'CL'],
            ['countrycode' => 'CHN', 'countryname' => 'China', 'code' => 'CN'],
            ['countrycode' => 'CXR', 'countryname' => 'Christmas Island', 'code' => 'CX'],
            ['countrycode' => 'CCK', 'countryname' => 'Cocos [Keeling] Islands', 'code' => 'CC'],
            ['countrycode' => 'COL', 'countryname' => 'Colombia', 'code' => 'CO'],
            ['countrycode' => 'COM', 'countryname' => 'Comoros', 'code' => 'KM'],
            ['countrycode' => 'COK', 'countryname' => 'Cook Islands', 'code' => 'CK'],
            ['countrycode' => 'CRI', 'countryname' => 'Costa Rica', 'code' => 'CR'],
            ['countrycode' => 'HRV', 'countryname' => 'Croatia', 'code' => 'HR'],
            ['countrycode' => 'CUB', 'countryname' => 'Cuba', 'code' => 'CU'],
            ['countrycode' => 'CUW', 'countryname' => 'Curacao', 'code' => 'CW'],
            ['countrycode' => 'CYP', 'countryname' => 'Cyprus', 'code' => 'CY'],
            ['countrycode' => 'CZE', 'countryname' => 'Czech Republic', 'code' => 'CZ'],
            ['countrycode' => 'COD', 'countryname' => 'Democratic Republic of the Congo', 'code' => 'CD'],
            ['countrycode' => 'DNK', 'countryname' => 'Denmark', 'code' => 'DK'],
            ['countrycode' => 'DJI', 'countryname' => 'Djibouti', 'code' => 'DJ'],
            ['countrycode' => 'DMA', 'countryname' => 'Dominica', 'code' => 'DM'],
            ['countrycode' => 'DOM', 'countryname' => 'Dominican Republic', 'code' => 'DO'],
            ['countrycode' => 'TLS', 'countryname' => 'East Timor', 'code' => 'TL'],
            ['countrycode' => 'ECU', 'countryname' => 'Ecuador', 'code' => 'EC'],
            ['countrycode' => 'EGY', 'countryname' => 'Egypt', 'code' => 'EG'],
            ['countrycode' => 'SLV', 'countryname' => 'El Salvador', 'code' => 'SV'],
            ['countrycode' => 'GNQ', 'countryname' => 'Equatorial Guinea', 'code' => 'GQ'],
            ['countrycode' => 'ERI', 'countryname' => 'Eritrea', 'code' => 'ER'],
            ['countrycode' => 'EST', 'countryname' => 'Estonia', 'code' => 'EE'],
            ['countrycode' => 'SWZ', 'countryname' => 'Eswatini', 'code' => 'SZ'],
            ['countrycode' => 'ETH', 'countryname' => 'Ethiopia', 'code' => 'ET'],
            ['countrycode' => 'FLK', 'countryname' => 'Falkland Islands', 'code' => 'FK'],
            ['countrycode' => 'FRO', 'countryname' => 'Faroe Islands', 'code' => 'FO'],
            ['countrycode' => 'FJI', 'countryname' => 'Fiji', 'code' => 'FJ'],
            ['countrycode' => 'FIN', 'countryname' => 'Finland', 'code' => 'FI'],
            ['countrycode' => 'FRA', 'countryname' => 'France', 'code' => 'FR'],
            ['countrycode' => 'GUF', 'countryname' => 'French Guiana', 'code' => 'GF'],
            ['countrycode' => 'PYF', 'countryname' => 'French Polynesia', 'code' => 'PF'],
            ['countrycode' => 'ATF', 'countryname' => 'French Southern Territories', 'code' => 'TF'],
            ['countrycode' => 'GAB', 'countryname' => 'Gabon', 'code' => 'GA'],
            ['countrycode' => 'GMB', 'countryname' => 'Gambia', 'code' => 'GM'],
            ['countrycode' => 'GEO', 'countryname' => 'Georgia', 'code' => 'GE'],
            ['countrycode' => 'DEU', 'countryname' => 'Germany', 'code' => 'DE'],
            ['countrycode' => 'GHA', 'countryname' => 'Ghana', 'code' => 'GH'],
            ['countrycode' => 'GIB', 'countryname' => 'Gibraltar', 'code' => 'GI'],
            ['countrycode' => 'GRC', 'countryname' => 'Greece', 'code' => 'GR'],
            ['countrycode' => 'GRL', 'countryname' => 'Greenland', 'code' => 'GL'],
            ['countrycode' => 'GRD', 'countryname' => 'Grenada', 'code' => 'GD'],
            ['countrycode' => 'GLP', 'countryname' => 'Guadeloupe', 'code' => 'GP'],
            ['countrycode' => 'GUM', 'countryname' => 'Guam', 'code' => 'GU'],
            ['countrycode' => 'GTM', 'countryname' => 'Guatemala', 'code' => 'GT'],
            ['countrycode' => 'GGY', 'countryname' => 'Guernsey', 'code' => 'GG'],
            ['countrycode' => 'GIN', 'countryname' => 'Guinea', 'code' => 'GN'],
            ['countrycode' => 'GNB', 'countryname' => 'Guinea-Bissau', 'code' => 'GW'],
            ['countrycode' => 'GUY', 'countryname' => 'Guyana', 'code' => 'GY'],
            ['countrycode' => 'HTI', 'countryname' => 'Haiti', 'code' => 'HT'],
            ['countrycode' => 'HMD', 'countryname' => 'Heard Island and McDonald Islands', 'code' => 'HM'],
            ['countrycode' => 'HND', 'countryname' => 'Honduras', 'code' => 'HN'],
            ['countrycode' => 'HKG', 'countryname' => 'Hong Kong', 'code' => 'HK'],
            ['countrycode' => 'HUN', 'countryname' => 'Hungary', 'code' => 'HU'],
            ['countrycode' => 'ISL', 'countryname' => 'Iceland', 'code' => 'IS'],
            ['countrycode' => 'IND', 'countryname' => 'India', 'code' => 'IN'],
            ['countrycode' => 'IDN', 'countryname' => 'Indonesia', 'code' => 'ID'],
            ['countrycode' => 'IRN', 'countryname' => 'Iran', 'code' => 'IR'],
            ['countrycode' => 'IRQ', 'countryname' => 'Iraq', 'code' => 'IQ'],
            ['countrycode' => 'IRL', 'countryname' => 'Ireland', 'code' => 'IE'],
            ['countrycode' => 'IMN', 'countryname' => 'Isle of Man', 'code' => 'IM'],
            ['countrycode' => 'ISR', 'countryname' => 'Israel', 'code' => 'IL'],
            ['countrycode' => 'ITA', 'countryname' => 'Italy', 'code' => 'IT'],
            ['countrycode' => 'CIV', 'countryname' => 'Ivory Coast', 'code' => 'CI'],
            ['countrycode' => 'JAM', 'countryname' => 'Jamaica', 'code' => 'JM'],
            ['countrycode' => 'JPN', 'countryname' => 'Japan', 'code' => 'JP'],
            ['countrycode' => 'JEY', 'countryname' => 'Jersey', 'code' => 'JE'],
            ['countrycode' => 'JOR', 'countryname' => 'Jordan', 'code' => 'JO'],
            ['countrycode' => 'KAZ', 'countryname' => 'Kazakhstan', 'code' => 'KZ'],
            ['countrycode' => 'KEN', 'countryname' => 'Kenya', 'code' => 'KE'],
            ['countrycode' => 'KIR', 'countryname' => 'Kiribati', 'code' => 'KI'],
            ['countrycode' => 'XKX', 'countryname' => 'Kosovo', 'code' => 'XK'],
            ['countrycode' => 'KWT', 'countryname' => 'Kuwait', 'code' => 'KW'],
            ['countrycode' => 'KGZ', 'countryname' => 'Kyrgyzstan', 'code' => 'KG'],
            ['countrycode' => 'LAO', 'countryname' => 'Laos', 'code' => 'LA'],
            ['countrycode' => 'LVA', 'countryname' => 'Latvia', 'code' => 'LV'],
            ['countrycode' => 'LBN', 'countryname' => 'Lebanon', 'code' => 'LB'],
            ['countrycode' => 'LSO', 'countryname' => 'Lesotho', 'code' => 'LS'],
            ['countrycode' => 'LBR', 'countryname' => 'Liberia', 'code' => 'LR'],
            ['countrycode' => 'LBY', 'countryname' => 'Libya', 'code' => 'LY'],
            ['countrycode' => 'LIE', 'countryname' => 'Liechtenstein', 'code' => 'LI'],
            ['countrycode' => 'LTU', 'countryname' => 'Lithuania', 'code' => 'LT'],
            ['countrycode' => 'LUX', 'countryname' => 'Luxembourg', 'code' => 'LU'],
            ['countrycode' => 'MAC', 'countryname' => 'Macao', 'code' => 'MO'],
            ['countrycode' => 'MKD', 'countryname' => 'Macedonia', 'code' => 'MK'],
            ['countrycode' => 'MDG', 'countryname' => 'Madagascar', 'code' => 'MG'],
            ['countrycode' => 'MWI', 'countryname' => 'Malawi', 'code' => 'MW'],
            ['countrycode' => 'MYS', 'countryname' => 'Malaysia', 'code' => 'MY'],
            ['countrycode' => 'MDV', 'countryname' => 'Maldives', 'code' => 'MV'],
            ['countrycode' => 'MLI', 'countryname' => 'Mali', 'code' => 'ML'],
            ['countrycode' => 'MLT', 'countryname' => 'Malta', 'code' => 'MT'],
            ['countrycode' => 'MHL', 'countryname' => 'Marshall Islands', 'code' => 'MH'],
            ['countrycode' => 'MTQ', 'countryname' => 'Martinique', 'code' => 'MQ'],
            ['countrycode' => 'MRT', 'countryname' => 'Mauritania', 'code' => 'MR'],
            ['countrycode' => 'MUS', 'countryname' => 'Mauritius', 'code' => 'MU'],
            ['countrycode' => 'MYT', 'countryname' => 'Mayotte', 'code' => 'YT'],
            ['countrycode' => 'MEX', 'countryname' => 'Mexico', 'code' => 'MX'],
            ['countrycode' => 'FSM', 'countryname' => 'Micronesia', 'code' => 'FM'],
            ['countrycode' => 'MDA', 'countryname' => 'Moldova', 'code' => 'MD'],
            ['countrycode' => 'MCO', 'countryname' => 'Monaco', 'code' => 'MC'],
            ['countrycode' => 'MNG', 'countryname' => 'Mongolia', 'code' => 'MN'],
            ['countrycode' => 'MNE', 'countryname' => 'Montenegro', 'code' => 'ME'],
            ['countrycode' => 'MSR', 'countryname' => 'Montserrat', 'code' => 'MS'],
            ['countrycode' => 'MAR', 'countryname' => 'Morocco', 'code' => 'MA'],
            ['countrycode' => 'MOZ', 'countryname' => 'Mozambique', 'code' => 'MZ'],
            ['countrycode' => 'MMR', 'countryname' => 'Myanmar', 'code' => 'MM'],
            ['countrycode' => 'NAM', 'countryname' => 'Namibia', 'code' => 'NA'],
            ['countrycode' => 'NRU', 'countryname' => 'Nauru', 'code' => 'NR'],
            ['countrycode' => 'NPL', 'countryname' => 'Nepal', 'code' => 'NP'],
            ['countrycode' => 'NLD', 'countryname' => 'Netherlands', 'code' => 'NL'],
            ['countrycode' => 'ANT', 'countryname' => 'Netherlands Antilles', 'code' => 'AN'],
            ['countrycode' => 'NCL', 'countryname' => 'New Caledonia', 'code' => 'NC'],
            ['countrycode' => 'NZL', 'countryname' => 'New Zealand', 'code' => 'NZ'],
            ['countrycode' => 'NIC', 'countryname' => 'Nicaragua', 'code' => 'NI'],
            ['countrycode' => 'NER', 'countryname' => 'Niger', 'code' => 'NE'],
            ['countrycode' => 'NGA', 'countryname' => 'Nigeria', 'code' => 'NG'],
            ['countrycode' => 'NIU', 'countryname' => 'Niue', 'code' => 'NU'],
            ['countrycode' => 'NFK', 'countryname' => 'Norfolk Island', 'code' => 'NF'],
            ['countrycode' => 'PRK', 'countryname' => 'North Korea', 'code' => 'KP'],
            ['countrycode' => 'MNP', 'countryname' => 'Northern Mariana Islands', 'code' => 'MP'],
            ['countrycode' => 'NOR', 'countryname' => 'Norway', 'code' => 'NO'],
            ['countrycode' => 'OMN', 'countryname' => 'Oman', 'code' => 'OM'],
            ['countrycode' => 'PAK', 'countryname' => 'Pakistan', 'code' => 'PK'],
            ['countrycode' => 'PLW', 'countryname' => 'Palau', 'code' => 'PW'],
            ['countrycode' => 'PSE', 'countryname' => 'Palestinian Territory', 'code' => 'PS'],
            ['countrycode' => 'PAN', 'countryname' => 'Panama', 'code' => 'PA'],
            ['countrycode' => 'PNG', 'countryname' => 'Papua New Guinea', 'code' => 'PG'],
            ['countrycode' => 'PRY', 'countryname' => 'Paraguay', 'code' => 'PY'],
            ['countrycode' => 'PER', 'countryname' => 'Peru', 'code' => 'PE'],
            ['countrycode' => 'PHL', 'countryname' => 'Philippines', 'code' => 'PH'],
            ['countrycode' => 'PCN', 'countryname' => 'Pitcairn', 'code' => 'PN'],
            ['countrycode' => 'POL', 'countryname' => 'Poland', 'code' => 'PL'],
            ['countrycode' => 'PRT', 'countryname' => 'Portugal', 'code' => 'PT'],
            ['countrycode' => 'PRI', 'countryname' => 'Puerto Rico', 'code' => 'PR'],
            ['countrycode' => 'QAT', 'countryname' => 'Qatar', 'code' => 'QA'],
            ['countrycode' => 'REU', 'countryname' => 'Reunion', 'code' => 'RE'],
            ['countrycode' => 'ROU', 'countryname' => 'Romania', 'code' => 'RO'],
            ['countrycode' => 'RUS', 'countryname' => 'Russia', 'code' => 'RU'],
            ['countrycode' => 'RWA', 'countryname' => 'Rwanda', 'code' => 'RW'],
            ['countrycode' => 'SHN', 'countryname' => 'Saint Helena', 'code' => 'SH'],
            ['countrycode' => 'KNA', 'countryname' => 'Saint Kitts and Nevis', 'code' => 'KN'],
            ['countrycode' => 'LCA', 'countryname' => 'Saint Lucia', 'code' => 'LC'],
            ['countrycode' => 'SPM', 'countryname' => 'Saint Pierre and Miquelon', 'code' => 'PM'],
            ['countrycode' => 'VCT', 'countryname' => 'Saint Vincent and the Grenadines', 'code' => 'VC'],
            ['countrycode' => 'WSM', 'countryname' => 'Samoa', 'code' => 'WS'],
            ['countrycode' => 'SMR', 'countryname' => 'San Marino', 'code' => 'SM'],
            ['countrycode' => 'STP', 'countryname' => 'Sao Tome and Principe', 'code' => 'ST'],
            ['countrycode' => 'SAU', 'countryname' => 'Saudi Arabia', 'code' => 'SA'],
            ['countrycode' => 'SEN', 'countryname' => 'Senegal', 'code' => 'SN'],
            ['countrycode' => 'SRB', 'countryname' => 'Serbia', 'code' => 'RS'],
            ['countrycode' => 'SYC', 'countryname' => 'Seychelles', 'code' => 'SC'],
            ['countrycode' => 'SLE', 'countryname' => 'Sierra Leone', 'code' => 'SL'],
            ['countrycode' => 'SGP', 'countryname' => 'Singapore', 'code' => 'SG'],
            ['countrycode' => 'SVK', 'countryname' => 'Slovakia', 'code' => 'SK'],
            ['countrycode' => 'SVN', 'countryname' => 'Slovenia', 'code' => 'SI'],
            ['countrycode' => 'SLB', 'countryname' => 'Solomon Islands', 'code' => 'SB'],
            ['countrycode' => 'SOM', 'countryname' => 'Somalia', 'code' => 'SO'],
            ['countrycode' => 'ZAF', 'countryname' => 'South Africa', 'code' => 'ZA'],
            ['countrycode' => 'SGS', 'countryname' => 'South Georgia and the South Sandwich Islands', 'code' => 'GS'],
            ['countrycode' => 'KOR', 'countryname' => 'South Korea', 'code' => 'KR'],
            ['countrycode' => 'SSD', 'countryname' => 'South Sudan', 'code' => 'SS'],
            ['countrycode' => 'ESP', 'countryname' => 'Spain', 'code' => 'ES'],
            ['countrycode' => 'LKA', 'countryname' => 'Sri Lanka', 'code' => 'LK'],
            ['countrycode' => 'SDN', 'countryname' => 'Sudan', 'code' => 'SD'],
            ['countrycode' => 'SUR', 'countryname' => 'Suriname', 'code' => 'SR'],
            ['countrycode' => 'SJM', 'countryname' => 'Svalbard and Jan Mayen', 'code' => 'SJ'],
            ['countrycode' => 'SWZ', 'countryname' => 'Swaziland', 'code' => 'SZ'],
            ['countrycode' => 'SWE', 'countryname' => 'Sweden', 'code' => 'SE'],
            ['countrycode' => 'CHE', 'countryname' => 'Switzerland', 'code' => 'CH'],
            ['countrycode' => 'SYR', 'countryname' => 'Syria', 'code' => 'SY'],
            ['countrycode' => 'TWN', 'countryname' => 'Taiwan', 'code' => 'TW'],
            ['countrycode' => 'TJK', 'countryname' => 'Tajikistan', 'code' => 'TJ'],
            ['countrycode' => 'TZA', 'countryname' => 'Tanzania', 'code' => 'TZ'],
            ['countrycode' => 'THA', 'countryname' => 'Thailand', 'code' => 'TH'],
            ['countrycode' => 'TLS', 'countryname' => 'Timor-Leste', 'code' => 'TL'],
            ['countrycode' => 'TGO', 'countryname' => 'Togo', 'code' => 'TG'],
            ['countrycode' => 'TKL', 'countryname' => 'Tokelau', 'code' => 'TK'],
            ['countrycode' => 'TON', 'countryname' => 'Tonga', 'code' => 'TO'],
            ['countrycode' => 'TTO', 'countryname' => 'Trinidad and Tobago', 'code' => 'TT'],
            ['countrycode' => 'TUN', 'countryname' => 'Tunisia', 'code' => 'TN'],
            ['countrycode' => 'TUR', 'countryname' => 'Turkey', 'code' => 'TR'],
            ['countrycode' => 'TKM', 'countryname' => 'Turkmenistan', 'code' => 'TM'],
            ['countrycode' => 'TCA', 'countryname' => 'Turks and Caicos Islands', 'code' => 'TC'],
            ['countrycode' => 'TUV', 'countryname' => 'Tuvalu', 'code' => 'TV'],
            ['countrycode' => 'UGA', 'countryname' => 'Uganda', 'code' => 'UG'],
            ['countrycode' => 'UKR', 'countryname' => 'Ukraine', 'code' => 'UA'],
            ['countrycode' => 'ARE', 'countryname' => 'United Arab Emirates', 'code' => 'AE'],
            ['countrycode' => 'GBR', 'countryname' => 'United Kingdom', 'code' => 'GB'],
            ['countrycode' => 'USA', 'countryname' => 'United States', 'code' => 'US'],
            ['countrycode' => 'UMI', 'countryname' => 'United States Minor Outlying Islands', 'code' => 'UM'],
            ['countrycode' => 'URY', 'countryname' => 'Uruguay', 'code' => 'UY'],
            ['countrycode' => 'UZB', 'countryname' => 'Uzbekistan', 'code' => 'UZ'],
            ['countrycode' => 'VUT', 'countryname' => 'Vanuatu', 'code' => 'VU'],
            ['countrycode' => 'VAT', 'countryname' => 'Vatican', 'code' => 'VA'],
            ['countrycode' => 'VEN', 'countryname' => 'Venezuela', 'code' => 'VE'],
            ['countrycode' => 'VNM', 'countryname' => 'Vietnam', 'code' => 'VN'],
            ['countrycode' => 'VGB', 'countryname' => 'Virgin Islands (British)', 'code' => 'VG'],
            ['countrycode' => 'VIR', 'countryname' => 'Virgin Islands (U.S.)', 'code' => 'VI'],
            ['countrycode' => 'WLF', 'countryname' => 'Wallis and Futuna', 'code' => 'WF'],
            ['countrycode' => 'ESH', 'countryname' => 'Western Sahara', 'code' => 'EH'],
            ['countrycode' => 'YEM', 'countryname' => 'Yemen', 'code' => 'YE'],
            ['countrycode' => 'ZMB', 'countryname' => 'Zambia', 'code' => 'ZM'],
            ['countrycode' => 'ZWE', 'countryname' => 'Zimbabwe', 'code' => 'ZW'],
        ]);
    }
}
