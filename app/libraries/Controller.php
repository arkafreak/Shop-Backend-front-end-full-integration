<?php
class Controller
{
    // Method to load the model
    public function model($model)
    {
        // Define the model path
        $modelPath = '../app/models/' . $model . '.php';

        // Check if the model file exists
        if (file_exists($modelPath)) {
            // Require the model file
            require_once $modelPath;
            // Instantiate and return the model instance
            return new $model();
        } else {
            // Error message if the model file does not exist
            die("Model '$model' does not exist.");
        }
    }

    // Method to load the view
    public function view($view, $data = [])
    {
        // Define the view path
        $viewPath = '../app/views/' . $view . '.php';

        // Check if the view file exists
        if (file_exists($viewPath)) {
            // Extract data to make it available in the view
            extract($data);
            // Require the view file
            require_once $viewPath;
        } else {
            // Error message if the view file does not exist
            die("View '$view' does not exist.");
        }
    }
}
