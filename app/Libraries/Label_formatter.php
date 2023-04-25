<?php
namespace App\Libraries;

/**
 * Handles reformatting of labels/column names
 */
class Label_Formatter {

    /**
     * Full label special formats - key should be the exact name of the column as returned by the database, value is the display text
     */
    private const LABEL_MAP = array(
        "file_size_mb" => "File Size (MB)",
        "file_size_kb" => "File Size (KB)",
        "plant_animal_tissue" => "Plant/Animal Tissue",
        "contact_usually_pnnl_staff" => "Contact (usually PNNL Staff)",
        "pubchem_cid" => "PubChem CID",
        "mts_pt_db_count" => "MTS PT DB Count",
        "mts_mt_db_count" => "MTS MT DB Count",
        "ms2_prec_z_1" => "MS2_PrecZ_1",
        "ms2_prec_z_2" => "MS2_PrecZ_2",
        "ms2_prec_z_3" => "MS2_PrecZ_3",
        "ms2_prec_z_4" => "MS2_PrecZ_4",
        "ms2_prec_z_5" => "MS2_PrecZ_5",
        "ms2_prec_z_more" => "MS2_PrecZ_more",
        "ms2_prec_z_likely_1" => "MS2_PrecZ_likely_1",
        "ms2_prec_z_likely_multi" => "MS2_PrecZ_likely_multi",
        "c_1a"   => "C_1A",
        "c_1b"   => "C_1B",
        "c_2a"   => "C_2A",
        "c_2b"   => "C_2B",
        "c_3a"   => "C_3A",
        "c_3b"   => "C_3B",
        "c_4a"   => "C_4A",
        "c_4b"   => "C_4B",
        "c_4c"   => "C_4C",
        "ds_1a"  => "DS_1A",
        "ds_1b"  => "DS_1B",
        "ds_2a"  => "DS_2A",
        "ds_2b"  => "DS_2B",
        "ds_3a"  => "DS_3A",
        "ds_3b"  => "DS_3B",
        "is_1a"  => "IS_1A",
        "is_1b"  => "IS_1B",
        "is_2"   => "IS_2",
        "is_3a"  => "IS_3A",
        "is_3b"  => "IS_3B",
        "is_3c"  => "IS_3C",
        "ms1_1"  => "MS1_1",
        "ms1_2a" => "MS1_2A",
        "ms1_2b" => "MS1_2B",
        "ms1_3a" => "MS1_3A",
        "ms1_3b" => "MS1_3B",
        "ms1_5a" => "MS1_5A",
        "ms1_5b" => "MS1_5B",
        "ms1_5c" => "MS1_5C",
        "ms1_5d" => "MS1_5D",
        "ms2_1"  => "MS2_1",
        "ms2_2"  => "MS2_2",
        "ms2_3"  => "MS2_3",
        "ms2_4a" => "MS2_4A",
        "ms2_4b" => "MS2_4B",
        "ms2_4c" => "MS2_4C",
        "ms2_4d" => "MS2_4D",
        "p_1a"   => "P_1A",
        "p_1b"   => "P_1B",
        "p_2a"   => "P_2A",
        "p_2b"   => "P_2B",
        "p_2c"   => "P_2C",
        "p_3"    => "P_3",
        "p_4a"   => "P_4A",
        "p_4b"   => "P_4B",
        "phos_2a" => "Phos_2A",
        "phos_2c" => "Phos_2C",
        "keratin_2a" => "Keratin_2A",
        "keratin_2c" => "Keratin_2C",
        "trypsin_2a" => "Trypsin_2A",
        "trypsin_2c" => "Trypsin_2C",
        "ms2_rep_ion_all" => "MS2_RepIon_All",
        "ms2_rep_ion_1missing" => "MS2_RepIon_1Missing",
        "ms2_rep_ion_2missing" => "MS2_RepIon_2Missing",
        "ms2_rep_ion_3missing" => "MS2_RepIon_3Missing",
        "inst_group" => "Inst. Group",
        "total_psms_msgf_filtered" => "Total PSMs (MSGF-filtered)",
        "unique_peptides_msgf_filtered" => "Unique Peptides (MSGF-filtered)",
        "unique_proteins_msgf_filtered" => "Unique Proteins (MSGF-filtered)",
        "total_psms_fdr_filtered" => "Total PSMs (FDR-filtered)",
        "unique_peptides_fdr_filtered" => "Unique Peptides (FDR-filtered)",
        "unique_proteins_fdr_filtered" => "Unique Proteins (FDR-filtered)",
        "fdr_threshold_pct" => "FDR Threshold (%)",
        // for production_instrument_stats page:
        "study_specific_datasets_per_day"     => "Study Specific Datasets per day",
        "emsl_funded_study_specific_datasets" => "EMSL-Funded Study Specific Datasets",
        "ef_study_specific_datasets_per_day"  => "EF Study Specific Datasets per day",
        "total_acq_time_days"                 => "Total AcqTimeDays",
        "study_specific_acq_time_days"        => "Study Specific AcqTimeDays",
        "ef_total_acq_time_days"              => "EF Total AcqTimeDays",
        "ef_study_specific_acq_time_days"     => "EF Study Specific AcqTimeDays",
        "hours_acq_time_per_day"              => "Hours AcqTime per Day",
        "inst_"                               => "Inst.",
        "pct_inst_emsl_owned"                 => "% Inst EMSL Owned",
        "ef_datasets_per_day"                 => "EF Datasets per day",
        "pct_blank_datasets"                  => "% Blank Datasets",
        "pct_qc_datasets"                     => "% QC Datasets",
        "pct_bad_datasets"                    => "% Bad Datasets",
        "pct_study_specific_datasets"         => "% Study Specific Datasets",
        "pct_ef_study_specific_datasets"      => "% EF Study Specific Datasets",
        "pct_ef_study_specific_by_acq_time"   => "% EF Study Specific by AcqTime"
    );

    /**
     * Single word special formats - key is the word (all lowercase), value is the display text for that word
     */
    private const WORD_MAP = array(
        "2d"     => "2D",
        "am"     => "AM",
        "amt"    => "AMT",
        "amts"   => "AMTs",
        "bpi"    => "BPI",
        "cid"    => "CID",
        "cpu"    => "CPU",
        "db"     => "DB",
        "dem"    => "DEM",
        "dia"    => "DIA",
        "dms"    => "DMS",
        "doi"    => "DOI",
        "ds"     => "DS",
        "dsinfo" => "DSInfo",
        "ef"     => "EF",
        "embl"   => "EMBL",
        "emsl"   => "EMSL",
        "epr"    => "EPR",
        "eta"    => "ETA",
        "etd"    => "ETD",
        "eus"    => "EUS",
        "fdr"    => "FDR",
        "fwhm"   => "FWHM",
        "ft"     => "FT",
        "fticr"  => "FTICR",
        "gb"     => "GB",
        "gc"     => "GC",
        "hcd"    => "HCD",
        "hms"    => "HMS",
        "hmsn"   => "HMSn",
        "hplc"   => "HPLC",
        "id"     => "ID",
        "ims"    => "IMS",
        "lc"     => "LC",
        "ltq"    => "LTQ",
        "masic"  => "MASIC",
        "maxquant" => "MaxQuant",
        "mb"     => "MB",
        "mrm"    => "MRM",
        "ms"     => "MS",
        "msn"    => "MSn",
        "ms1"    => "MS1",
        "ms2"    => "MS2",
        "msfragger" => "MSFragger",
        "msgf"   => "MSGF",
        "msms"   => "MSMS",
        "mt"     => "MT",
        "myesml" => "MyEMSL",
        "ncbi"   => "NCBI",
        "pi"     => "PI",
        "pm"     => "PM",
        "pnnl"   => "PNNL",
        "ppm"    => "PPM",
        "prism"  => "PRISM",
        "prn"    => "PRN",
        "psm"    => "PSM",
        "psms"   => "PSMs",
        "pt"     => "PT",
        "qc"     => "QC",
        "qcart"  => "QCART",
        "qcdm"   => "QCDM",
        "qqq"    => "QQQ",
        "q1ms"   => "Q1MS",
        "q3ms"   => "Q3MS",
        "rt"     => "RT",
        "sa"     => "SA",
        "sha1"   => "SHA1",
        "smaqc"  => "SMAQC",
        "srm"    => "SRM",
        "tic"    => "TIC",
        "tsq"    => "TSQ",
        "url"    => "URL",
        "viper"  => "VIPER",
        "wp"     => "WP",
        "wpn"    => "WPN",
        "xic"    => "XIC"
    );

    // --------------------------------------------------------------------
    function __construct() {
        // Get a copy of LABEL_MAP with keys and values swapped
        $this->label_map_flipped = array_flip(self::LABEL_MAP);
    }

    private $label_map_flipped;

    /**
     * Format the label to a more readable form
     * @param type $label_text
     */
    public function format($label_text) {
        if (array_key_exists($label_text, self::LABEL_MAP)) {
            return self::LABEL_MAP[$label_text];
        }

        $words = explode("_", $label_text);
        $formatted = array();
        foreach ($words as $word) {
            if (array_key_exists($word, self::WORD_MAP)) {
                $formatted[] = self::WORD_MAP[$word];
            } else {
                $formatted[] = ucwords($word);
            }
        }

        return implode(" ", $formatted);
    }

    /**
     * De-Format the label to the lowercase, '_'-interpolated form
     * @param type $label_text
     */
    public function deformat($label_text)
    {
        // array_search()/in_array() takes much longer to run than array_key_exists (apparently several orders of magnitude on large lists, see https://gist.github.com/alcaeus/536156663fac96744eba77b3e133e50a)
        //$labelMatch = array_search($label_text, self::LABEL_MAP);
        //if ($labelMatch !== false) {
        //    return $labelMatch;
        //}

        if (array_key_exists($label_text, $this->label_map_flipped)) {
            return $this->label_map_flipped[$label_text];
        }

        // TODO: Currently all entries in WORD_MAP are capitalization corrections. If that changes, then we also need to do word-by-word deformatting.

        $lower = strtolower($label_text);
        return preg_replace("/[ ()_.]+/", "_", $lower);
    }
}
?>
