<?php
/**
 * CSV provides a base that builds a CSV file or string
 *
 * @author Linus Norton <linus.norton@assertis.co.uk>
 */
class CSV {
    private $separator;
    private $newLine;
    private $quote;
    private $escape;
    private $fields;
    private $lineNumber;

    /**
     *
     * @param string $separator
     * @param string $newLine
     * @param string $quote
     * @param string $escape
     */
    public function __construct($separator = ",",
                                $newLine = "\n",
                                $quote = '"',
                                $escape = true) {
        $this->separator = $separator;
        $this->newLine = $newLine;
        $this->quote = $quote;
        $this->escape = $escape;
        $this->fields = array();
        $this->lineNumber = 0;
    }

    /**
     * Add a single value to the csv
     * @param string $value
     */
    public function add($value) {
        $this->fields[$this->lineNumber][] = $this->quote.addslashes($value).$this->quote;
    }

    /**
     * Add a whole line to the csv
     * @param arary $line
     */
    public function addLine(array $line) {
        foreach ($line as $field) {
            $this->add($field);
        }

        $this->newLine();
    }

    /**
     * insert a new line into the csv
     */
    public function newLine() {
        $this->lineNumber++;
    }

    /**
     * Return the csv in string format
     * @return string $csv
     */
    public function build() {
        $csv = "";

        foreach ($this->fields as $line) {
            $csv .= implode($this->separator, $line).$this->newLine;
        }

        return $csv;
    }

    /**
     * Echo the outputs to the screen, you will probably want to die after this
     */
    public function output() {
        header("content-type: text/csv");
        echo $this->build();
    }

    /**
     * Write the CSV to a file
     * @param string $file
     */
    public function writeToFile($file) {
        if (!file_put_contents($file, $this->build(), FILE_TEXT)) {
            throw new FrameEx("Unable to write CSV file to: ".$file);
        }
    }
}
