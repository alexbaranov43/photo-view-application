<?php
namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\PhotosResource;
use App\Http\Resources\PhotosResourceCollection;

use Illuminate\Http\Request;

class PhotoController extends Controller
{

    private $invalidEntryCount = 0;
    private $invalidEntries = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          
        $photos = Photo::select(
                'photos.id',
                'photos.image_url',
                'photos.width',
                'photos.height',
                'users.id as user_id',
                'photos.created_at'
            )
                ->join('users', 'users.id', '=', 'photos.user_id')
                ->where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
                return new PhotosResourceCollection($photos);


        // return response()->json([

        //     'photos' =>  Photo::select(
        //         'photos.id',
        //         'photos.image_url',
        //         'photos.width',
        //         'photos.height',
        //         'users.id as user_id',
        //         'photos.created_at'
        //     )
        //         ->join('users', 'users.id', '=', 'photos.user_id')
        //         ->where('user_id', auth()->user()->id)
        //         ->orderBy('created_at', 'desc')
        //         ->paginate(10)

        // ], 200);
    }

    public function indexByDimension(Request $request, $width, $height)
    {
                $photos =  Photo::select(
                'photos.id',
                'photos.image_url',
                'photos.width',
                'photos.height',
                'users.id as user_id',
                'photos.created_at'
            )
                ->join('users', 'users.id', '=', 'photos.user_id')
                ->where('user_id', auth()->user()->id)
                ->where('width', $width)
                ->where('height', $height)
                ->orderBy('created_at', 'desc')
                ->get();

                return new PhotosResourceCollection($photos);
        // return response()->json([



        // ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('photos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate POST values
        // Check that POSTed file exists
        $rules = [
            'csv_file' => 'required|file'
        ];

        $messages = [
            'csv_file.required' => 'Please select a CSV file type'
        ];

        // check if the file is valid
        $validator = Validator::make($request->all(), $rules, $messages);
        // dd($validator);
        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }
        // Get the POSTed file path
        // get the file temp path
        $filepath = request()->file('csv_file')->path();

        // create the csv reader config
        $lexer = new Lexer(new LexerConfig());

        // create the interpreter
        $interpreter = new Interpreter();

        $batchSize = 100;

        $dataToWrite = [];
        $importedRowCount = 0;
        $lineCount = 0;
        $errorLines = [];
        $interpreter->addObserver(function (array $row) use ($batchSize, &$dataToWrite, &$importedRowCount, &$errorLines, &$lineCount) {
            $importedRowCount++;
            $lineCount++;

            // use REG EX to split the url parameters
            $url = $row[0];
            $urlDataArray = preg_split("/\//", $url);
            $photoId = $urlDataArray[4];
            $width = $urlDataArray[5];
            $height = $urlDataArray[6];

            $data = [
                'user_id' => auth()->user()->id,
                'image_url' => $row[0],
                'photo_id' => $photoId,
                'width' => $width,
                'height' => $height,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            // Validating each column of row data for proper type
            if (
                is_numeric($data['user_id'])
                && $data['user_id'] = auth()->user()->id
                && is_string($data['image_url'])
                && is_numeric($data['photo_id'])
                && is_numeric($data['width'])
                && is_numeric($data['height'])
                
            ) {
                $dataToWrite[] = $data;
            } else {
                // put invalid entries into an array to see which metrics failed
                // $data['errorLine'] == $lineCount;
                $this->invalidEntries[] = $data;
                $errorLines[] = $lineCount;
                $importedRowCount--;
                $this->invalidEntryCount++;
                return;
            }

            if (count($dataToWrite) >= $batchSize) {
                // Create database insert statement
                $this->insertCsvData($dataToWrite);

                // reset the dataToWrite
                $dataToWrite = [];
            }
        });
        $lexer->parse($filepath, $interpreter);

        if (count($dataToWrite) > 0) {
            // Insert any remaining data into the database
            $this->insertCsvData($dataToWrite);
        }


        $message = $importedRowCount . ' Content Scenario Metrics were successfully imported!';
        $invalidEntries = $this->invalidEntries;
        $invalidEntryCount = count($this->invalidEntries); // $this->invalidEntryCount;
        $warning = $invalidEntryCount . ' Content Scenario Metrics were invalid.';

        redirect('/photos');
        return view('photos.create', compact('message', 'invalidEntries', 'invalidEntryCount', 'importedRowCount', 'warning', 'errorLines'))->with('invalidEntries', $invalidEntries);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Download the CSV File for parsing
    public function downloadCsv()
    {
        $file = public_path() . '/csv/pic-data.csv';
        return Response::download($file, 'pic-data.csv');
    }

    private function insertCsvData($data)
    {
        $columnCount = count($data[0]);
        $rowCount = count($data);
        $totalDataItems = $columnCount * $rowCount;

        /* Fill in chunks with '?' and separate them by group of $columnCount */
        $args = implode(',', array_map(
            function ($el) {
                return '(' . implode(',', $el) . ')';
            },
            array_chunk(array_fill(0, $totalDataItems, '?'), $columnCount)
        ));

        $params = array();
        foreach ($data as $row) {
            foreach ($row as $value) {
                $params[] = $value;
            }
        }

        $query = "INSERT INTO `photos` (user_id, image_url, photo_id, width, height, created_at, updated_at) VALUES " . $args;
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
    }
}
