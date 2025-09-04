<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Spatie\Html\Html;
use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\DBAL\Schema\Column;



class DataManagementController extends Controller
{
    protected $columnTypesMapping = [
        'bigint' => 'number',
        'integer' => 'number',
        'smallint' => 'number',
        'tinyint' => 'number',
        'mediumint' => 'number',
        'int' => 'number',
        'decimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'string' => 'text',
        'char' => 'text',
        'varchar' => 'text',
        'text' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'date' => 'date',
        'datetime' => 'datetime-local',
        'timestamp' => 'datetime-local',
        'time' => 'time',
        'year' => 'number',
        'enum' => 'select',
        'set' => 'select',
        'boolean' => 'text',
    ];

    public function index()
    {
        $models = $this->getModels();
        return view('backend.data_management.index', compact('models'));
    }


    public function create($model)
    {
        $modelInstance = app()->make("App\Models\\$model");
        $model_table = $modelInstance->getTable();
        $columns = Schema::getColumnListing($model_table);
        $column_info = [];
        $columnDefaults = [];
        $columnComments = [];
        $nullableColumns = [];
        $requiredColumns = [];
        $columnMaxLengths = [];

        // Use Doctrine DBAL's Schema Manager to get column defaults, lengths, and comments
        $connection = app('db')->connection()->getDoctrineConnection();
        $schemaManager = new MySqlSchemaManager($connection);
        $databaseName = config('database.connections.mysql.database');

        foreach ($columns as $column) {
            $column_info[$column] = Schema::getColumnType($model_table, $column);
            $columnDefaults[$column] = $this->getColumnDefault($schemaManager, $model_table, $column);
            $columnComments[$column] = $this->getColumnComment($schemaManager, $model_table, $column);
            $nullableColumns[$column] = $this->isColumnNullable($schemaManager, $model_table, $column);
            $columnMaxLengths[$column] = $this->getColumnMaxLength($schemaManager, $model_table, $column);
            if (!$nullableColumns[$column] && is_null($columnDefaults[$column])) {
                $requiredColumns[] = $column;
            }
        }

        $columnTypesMapping = $this->columnTypesMapping;

        return view('backend.data_management.create', compact('model', 'columns', 'column_info', 'columnTypesMapping', 'columnComments', 'requiredColumns', 'columnMaxLengths'));
    }



    public function store(Request $request, $model)
    {
        $modelInstance = app()->make("App\Models\\$model");

        // Get the column names and their data types of the model's table
        $columnInfo = Schema::getColumnListing($modelInstance->getTable());
        $columnTypes = [];
        foreach ($columnInfo as $column) {
            $columnTypes[$column] = Schema::getColumnType($modelInstance->getTable(), $column);
        }

        // Create an empty array to hold the data for creation
        $data = [];

        // Iterate through the column names
        foreach ($columnInfo as $column) {
            // Check if the request contains data for this column
            if ($request->has($column) && $request->filled($column)) {
                // Parse input if column type is 'timestamp' or 'datetime'
                if ($columnTypes[$column] === 'timestamp' || $columnTypes[$column] === 'datetime') {
                    $modelInstance->$column = Carbon::parse($request->input($column));
                } else {
                    // Set the value for the column from the request
                    $modelInstance->$column = $request->input($column);
                }
            }
        }

        // Create a new record with the extracted data
        $modelInstance->fill($data)->save();

        return redirect()->route('admin.database_admin.show', $model);
    }

    public function show($model)
    {
        $modelInstance = app()->make("App\Models\\$model");
        $records = $modelInstance::paginate(50);
        $columns = Schema::getColumnListing($modelInstance->getTable());

        return view('backend.data_management.show', compact('model', 'records', 'columns'));
    }


    public function edit($model, $id)
    {
        $modelInstance = app()->make("App\Models\\$model");
        $record = $modelInstance::find($id);
        $model_table = $modelInstance->getTable();
        $columns = Schema::getColumnListing($model_table);
        $column_info = [];
        $columnDefaults = [];
        $columnComments = [];
        $nullableColumns = [];
        $requiredColumns = [];

        // Use Doctrine DBAL's Schema Manager to get column defaults and comments
        $connection = app('db')->connection()->getDoctrineConnection();
        $schemaManager = new MySqlSchemaManager($connection);
        $databaseName = config('database.connections.mysql.database');

        foreach ($columns as $column) {
            $column_info[$column] = Schema::getColumnType($model_table, $column);
            $columnDefaults[$column] = $this->getColumnDefault($schemaManager, $model_table, $column);
            $columnComments[$column] = $this->getColumnComment($schemaManager, $model_table, $column);
            $nullableColumns[$column] = $this->isColumnNullable($schemaManager, $model_table, $column);
            $columnMaxLengths[$column] = $this->getColumnMaxLength($schemaManager, $model_table, $column);
            if (!$nullableColumns[$column] && is_null($columnDefaults[$column])) {
                $requiredColumns[] = $column;
            }
        }

        $columnTypesMapping = $this->columnTypesMapping;

        return view('backend.data_management.edit', compact('model', 'record', 'columns', 'column_info', 'columnTypesMapping', 'columnComments', 'requiredColumns', 'model_table', 'columnMaxLengths'));
    }

    protected function getColumnDefault($schemaManager, $table, $column)
    {
        try {
            $columnDetails = $schemaManager->listTableDetails($table)->getColumn($column);
            return $columnDetails->getDefault();
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getColumnComment($schemaManager, $table, $column)
    {
        try {
            $columnDetails = $schemaManager->listTableDetails($table)->getColumn($column);
            return $columnDetails->getComment();
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function isColumnNullable($schemaManager, $table, $column)
    {
        try {
            $columnDetails = $schemaManager->listTableDetails($table)->getColumn($column);
            return $columnDetails->getNotNull() ? false : true;
        } catch (\Exception $e) {
            return true;
        }
    }

    protected function getColumnMaxLength($schemaManager, $table, $column)
    {
        try {
            $columnDetails = $schemaManager->listTableDetails($table)->getColumn($column);
            return $columnDetails->getLength() == 0 ? 191 : $columnDetails->getLength();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $model, $id)
    {
        $modelInstance = app()->make("App\Models\\$model");
        $record = $modelInstance::find($id);

        // Get the column names and their data types of the model's table
        $columnInfo = Schema::getColumnListing($modelInstance->getTable());
        $columnTypes = [];
        foreach ($columnInfo as $column) {
            $columnTypes[$column] = Schema::getColumnType($modelInstance->getTable(), $column);
        }

        // Iterate through the column names
        foreach ($columnInfo as $column) {
            // Check if the request contains data for this column
            if ($request->has($column) && $request->filled($column)) {
                // Parse input if column type is 'timestamp' or 'datetime'
                if ($columnTypes[$column] === 'timestamp' || $columnTypes[$column] === 'datetime') {
                    $record->$column = Carbon::parse($request->input($column));
                } else {
                    // Update the value for the column from the request
                    $record->$column = $request->input($column);
                }
            }
        }

        // Save the updated record
        $record->save();

        return redirect()->route('admin.database_admin.show', $model);
    }

    public function destroy($model, $id)
    {
        $modelInstance = app()->make("App\Models\\$model");
        $record = $modelInstance::find($id);
        $record->delete();

        return redirect()->route('admin.database_admin.show', $model);
    }

    protected function getModels()
    {
        $models = [];
        foreach (glob(app_path('Models') . '/*.php') as $filename) {
            $models[] = basename($filename, '.php');
        }
        return $models;
    }
}
