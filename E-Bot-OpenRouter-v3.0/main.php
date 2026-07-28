<?php
session_start();

// ============================================================
// This file used to be split across main.php, php/php_utils_revised.php
// and php/name_config.php. It has been consolidated into this single
// backend file, alongside index.php, to simplify the codebase.
// ============================================================


// ERROR LOGGING
// Php errors are logged to a file named: php-errors.log
// This file will be automatically created the first time
// an error occurs.


// ADD YOUR OPENROUTER API KEY
// Get a key from https://openrouter.ai/keys and add it to the
// ebot_config.ini.txt file. Change the file name from
// ebot_config.ini.txt to ebot_config.ini
// The ebot_config.ini file gets loaded in the php funtion that
// makes the API request.


// *** IMPORTANT SECURITY NOTE ***
// The ebot_config.ini file is currently located inside the website root folder.
// Please Secure your API Key by moving the ebot_config.ini file
// to a folder that's located outside your website root folder.
// Specify the path to the ebot_config.ini file here.

$path_to_config_ini = 'ebot_config.ini';


// OpenRouter uses a single, OpenAI-compatible chat completions endpoint.
// The specific model (and provider) are chosen per-request in the
// request body, not via the URL - see the agent configs below.
$url = "https://openrouter.ai/api/v1/chat/completions";

// OpenRouter uses these headers to attribute/rank apps on openrouter.ai.
// They're optional but recommended. Update to match your own site.
$site_url = "https://example.com";
$site_title = "E-Bot";



//-----------------------------
// Agent model / provider config
//-----------------------------
// Each agent (proofreader, chat, translation) specifies its own
// OpenRouter model and provider. They currently all use the same
// model, but can be tuned independently later - e.g. giving the
// chat agent a stronger model than the proofreader/translator.

$proofreader_agent_config = [
	"model_id" => "qwen/qwen3.5-flash-02-23",
	"provider" => "alibaba",
];

$chat_agent_config = [
	"model_id" => "qwen/qwen3.5-flash-02-23",
	"provider" => "alibaba",
];

$translation_agent_config = [
	"model_id" => "qwen/qwen3.5-flash-02-23",
	"provider" => "alibaba",
];



//-----------
// Settings
//-----------

$temperature = 0.5;

$max_tokens = 300;

// Maximum number of user/model turn PAIRS to keep in the chat history.
// Once this is exceeded, the oldest pair is dropped (FIFO), one pair
// at a time, so a user turn and its matching model turn are always
// removed together - the history never ends up with a dangling turn,
// and images are only ever lost as part of the pair they belong to.
$max_history_pairs = 20;

// Set how fast the text is spoken
$speech_rate = 1;


// English (British)
$bot_language = "British English";
$speech_lang_code = "en-GB";

/* 
It's important to choose a voice that can speak
the selected language i.e. that matches the lang code.
This is the JS code that you can run to get the available
voices. Change the language code to suit.

<script>
speechSynthesis.onvoiceschanged = () => {
  const voices = speechSynthesis.getVoices();
  voices
    .filter(v => v.lang === 'en-GB')
    .forEach(v => console.log(`${v.name} (${v.lang})`));
};
</script>
*/
$speech_voice_name = "Serena"; 


/*
// Spanish
$bot_language = "Spanish";
$speech_lang_code = "es-ES";
$speech_voice_name = "Jorge";
*/


// If the message history session variable does NOT existt
if (!isset($_SESSION['message_history'])) {
	
	// Create a message_history list
	$_SESSION['message_history'] = array();
	$message_history = $_SESSION['message_history'];
	
	// Randomly set the chatbot's mood.
	// This stays the same for the entire session.
	$mood_list = array('bubbly', 'contemplative', 'cheerful');
	$length = count($mood_list);
	$limit = $length - 1; 
	$randomNumber = random_int(0, $limit); // the $limit is inclusive
	$mood = $mood_list[$randomNumber];
	
	// Remember: The system message is only set once in the message history.
	$_SESSION['emotion'] = $mood;
	
} else {
	
	// Assign the session variable
	$message_history = $_SESSION['message_history'];
	
	
	// Remember: The system message is only set once in the message history.
	$mood = $_SESSION['emotion'];
}





// This function cleans and secures the user input
function test_input(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		//$data = htmlentities($data);
		
		return $data;
	}

	/**
	 * Backstop for the "LLM always ends with two questions" habit.
	 * If the reply's last two sentences both end in "?", drop the final
	 * question so the reply ends on its second-to-last sentence instead.
	 * This only trims a trailing pair; it does not touch questions earlier
	 * in the reply.
	 */
	function limit_trailing_questions($text) {
		// Split into sentences, keeping the punctuation attached.
		preg_match_all('/[^.!?]+[.!?]+(?:\s+|$)/u', $text, $matches);
		$sentences = $matches[0];

		if (count($sentences) < 2) {
			return $text;
		}

		$last  = trim(end($sentences));
		$second_to_last = trim($sentences[count($sentences) - 2]);

		$last_is_question = substr($last, -1) === '?';
		$second_is_question = substr($second_to_last, -1) === '?';

		if ($last_is_question && $second_is_question) {
			// Drop the final sentence, keep everything before it.
			array_pop($sentences);
			$text = trim(implode('', $sentences));
		}

		return $text;
	}
	


// This code is triggered when the user submits a message.
// The form data arrives here via Ajax.
if ((isset($_REQUEST["my_message"]) || !empty($_REQUEST["my_image"])) && empty($_REQUEST["robotblock"])) {
	
	
	
	// Initialize variables
	$corrected_user_message = "none";
	$translated_chat_agent_response = "none";
	
	
	// Check the status of the radio buttons
	if (isset($_REQUEST["speak1"])) {
		$speak_request = 'selected';
	} else {
		$speak_request = 'not_selected';	
	}
	
	if (isset($_REQUEST["correct1"])) {
		$correction_request = 'selected';
	} else {
		$correction_request = 'not_selected';	
	}
	
	if (isset($_REQUEST["translate1"])) {
		$translation_request = 'selected';
	} else {
		$translation_request = 'not_selected';	
	}
	
	
	
	// Get the user's first language
	$translation_language = $_REQUEST["user_language"];
	
	
	// Get the user's message.
	// This can legitimately be empty now - the user may have sent
	// an image with no accompanying text.
	$user_message = isset($_REQUEST["my_message"]) ? $_REQUEST["my_message"] : '';
	
	
	
	// Clean and secure the user's text input
	$user_message = test_input($user_message);
	
	
	// Make a copy of the user message without any corrections.
	// If the proofreader_agent API call fails then
	// this uncorrected user message will be sent to the chat_agent.
	$uncorrected_user_message = $user_message;
	
	
	// Get the user's image, if one was attached. The frontend already
	// compresses and encodes it client-side into a data URI (e.g.
	// "data:image/jpeg;base64,..."), so this is only a lightweight
	// sanity check on the shape of that string, not a security boundary.
	$user_image_data_url = '';
	if (!empty($_REQUEST["my_image"])) {
		$candidate_image = $_REQUEST["my_image"];
		if (preg_match('/^data:image\/(png|jpe?g|webp|gif);base64,[A-Za-z0-9+\/=]+$/', $candidate_image)) {
			$user_image_data_url = $candidate_image;
		}
	}
	
	
	//---------------------------
	// Run the proofreader agent
	//---------------------------
	// Checks the user message for errors
	
	

		
// Proofreader Agent
// Only runs when there is actual text to check - an image-only
// message has nothing for it to proofread.
if (trim($user_message) !== '') {

$proofreader_agent_system_message = <<<EOT
You are a highly skilled {$bot_language} language proofreader. You will be given {$bot_language} text delimited by triple hash tags (###). Your task is to correct EVERY spelling, punctuation and grammar error in the text - not just the most obvious one. Use British English spelling conventions (e.g. "colour" not "color", "organise" not "organize") - treat American spellings as errors to correct.

Check specifically for these common learner mistakes, in addition to anything else you spot:
- Missing or wrong forms of "to be" (e.g. "I fine" -> "I'm fine", "he happy" -> "he's happy")
- Missing or wrong auxiliary/helping verbs (e.g. "I go there yesterday" -> "I went there yesterday")
- Subject-verb agreement (e.g. "she like" -> "she likes")
- Missing articles ("a", "an", "the") where one is needed
- Wrong or missing prepositions
- Verb tense errors
- Expanding informal abbreviations to standard words (e.g. "thx" -> "thanks", "u" -> "you") - but do NOT stop there if other errors remain in the same message; fix all of them together

Do not stop after fixing the first or most obvious error - re-read the whole sentence afterwards and check it is now fully correct.

Example:
Input: ###I fine thx###
Output correction: "I'm fine, thanks."

Think step by step. Return your corrected text. If the original text does not contain any errors at all then respond with: ---.
	Respond in a consistent format. Output a JSON string with the following schema:
{
"correction": <"Your corrected version of the user_message or ---.">
}
	
EOT;
		
		// Remove any html	
		$user_message = strip_tags($user_message);
		
		$text_to_proofread = "###" . $user_message . "###";
		$GLOBALS['last_api_usage'] = null;
		$corrected_user_message_list = run_agent_without_memory(
			$proofreader_agent_system_message,
			$text_to_proofread,
			$proofreader_agent_config['model_id'],
			$proofreader_agent_config['provider']
		);
		$proofreader_usage = $GLOBALS['last_api_usage'];
		
		// Process the response
		if ($corrected_user_message_list[0] != "is_plain_text") {

			// It is json
			$corrected_user_message = $corrected_user_message_list[1]["correction"];
			$corrected_user_message = trim($corrected_user_message);
		} else {

			// It is plain text
			$corrected_user_message = $corrected_user_message_list[1];
			$corrected_user_message = trim($corrected_user_message);
		}
		
		
		// Extract the text from the string
		$corrected_user_message = replaceItemsInString($corrected_user_message);
		
		// Some models return the user's text back unchanged (instead of
		// the requested '---' marker) when there are no errors to fix.
		// Treat an identical correction as "no errors found" so the rest
		// of the code (which already knows how to handle '---') behaves
		// the same way it would if the model had followed the instruction.
		if (trim($corrected_user_message) === trim($user_message)) {
			$corrected_user_message = '---';
		}
		
	

	
	
	
	
	//---------------------
	// Run the chat agent
	//---------------------
	// Creates the responses to
	// the users chat messages
	
	
	// We get a better non-english response
	// if a corrected user message is passed to the chat agent.
	// The proofreader_agent returns '---' if no errors were found.
	
	
	
	
	// Sometimes the model outputs two dashes ('--') instead of three dashes ('---')
	if ($corrected_user_message == '---' || $corrected_user_message == '--') {
		$input_message = $uncorrected_user_message;
	} else {
		$input_message = $corrected_user_message;
	}

} else {

	// Image-only message - there's no text to correct or pass through.
	$input_message = '';
	$proofreader_usage = null;

}
	
	

$chat_agent_system_message = <<<EOT
You are a friendly {$bot_language} language teacher. You always respond in {$bot_language}.
Your name is E-Bot. It is short for English Bot. The user is interacting with you using a web app called E-Bot.
Your role is to help users practice {$bot_language} through natural conversation.
The user's words may be captured through speech recognition, which may contain mistakes. Be understanding and adapt to possible errors in their speech.
Your replies are converted into speech using SpeechSynthesis, so keep your sentences clear, natural, and easy to pronounce.
You speak with a friendly, casual, and approachable female voice.
Keep the conversation flowing in a natural, relaxed way — like a friend chatting — not like an assistant offering help.
Make comments, share little thoughts, and react naturally to the user's messages.
Avoid robotic language. Stay human-like and engaging.
Always use British English spelling and vocabulary (e.g. "colour" not "color", "organise" not "organize", "favourite" not "favorite").
Do not use casual American slang, including words like "buddy" and "chilling". Choose natural British alternatives instead where a casual tone is called for.
Use clear, simple, neutral English. Avoid idioms, phrasal expressions, and regional turns of phrase (e.g. "settling in for a chat", "chew the fat") - these are hard for non-native speakers to parse. Say things plainly and directly (e.g. "How are you?" instead of "How are you getting on?"), while still sounding warm and natural.
The user has the ability to send you images. Therefore, when roleplaying ordering food, for example, you can ask the user if they want to send you a photo of the menu.
Keep your responses concise.
Ask at most one question per reply, and only when it genuinely fits the conversation. Do not end every message with a question — plenty of replies should just be a comment, reaction, or statement with no question at all. Never stack two questions in the same reply, and never end a reply with two questions in a row.
EOT;


	
	$parts_list = array();
	if (trim($input_message) !== '') {
		$parts_list[] = array("text" => $input_message);
	}
	if (!empty($user_image_data_url)) {
		$parts_list[] = array("image" => $user_image_data_url);
	}
	$message_history[] = array("role" => "user", "parts" => $parts_list);
	
	$GLOBALS['last_api_usage'] = null;
	$chat_agent_response_list = run_agent_with_memory(
		$chat_agent_system_message,
		$message_history,
		$chat_agent_config['model_id'],
		$chat_agent_config['provider']
	);
	$chat_usage = $GLOBALS['last_api_usage'];
	// This response is always plain text
	$chat_agent_response = $chat_agent_response_list[1];
	
	// Backstop: trim a trailing double-question if the prompt-level
	// instruction didn't catch it.
	$chat_agent_response = limit_trailing_questions($chat_agent_response);
	
	
	// This text will be spoken out loud
	$text_to_speak = test_input($chat_agent_response);
	
	// Update the chat history
	$message_dict = array("text" => $chat_agent_response);
	$parts_list = array();
	$parts_list[] = $message_dict;
	$message_history[] = array("role" => "model", "parts" => $parts_list);
	
	// Trim the history to $max_history_pairs, oldest pair first.
	// A "pair" is a user turn immediately followed by its model turn,
	// so this always removes both halves together.
	while (count($message_history) > $max_history_pairs * 2) {
		array_shift($message_history); // oldest user turn
		array_shift($message_history); // its matching model turn
	}
	
	$_SESSION['message_history'] = $message_history;
	
	
	
	
	
	
	
	//---------------------------
	// Run the translation agent
	//---------------------------
	// Translates the chat agent's response
	// into the user's first language.

	
	if ($translation_request == 'selected' && $user_message != 'api_error' && $user_message != 'Sorry. Something went wrong. Please try again.') {
			
		
// Translation Agent
$translation_agent_system_message = <<<EOT
You are a highly skilled {$translation_language} translator. You will be given text. You task is to translate the text into {$translation_language}. Return your translated text.
If {$translation_language} is English, use British English spelling and vocabulary (e.g. "colour" not "color", "organise" not "organize"), and avoid casual American slang such as "buddy" or "chilling".
	Respond in a consistent format. Output a JSON string with the following schema:
{
"translation": "<Your translated version of the text.>"
}
	
EOT;
		
		// Remove any html
		$chat_agent_response = strip_tags($chat_agent_response);
		
		$GLOBALS['last_api_usage'] = null;
		$translated_chat_agent_response_list = run_agent_without_memory(
			$translation_agent_system_message,
			$chat_agent_response,
			$translation_agent_config['model_id'],
			$translation_agent_config['provider']
		);
		$translation_usage = $GLOBALS['last_api_usage'];
		
		
		// Process the response
		if ($translated_chat_agent_response_list[0] != "is_plain_text") {
			// It is json
			$translated_chat_agent_response = $translated_chat_agent_response_list[1]["translation"];
		} else {
			// It is plain text
			$translated_chat_agent_response = $translated_chat_agent_response_list[1];
		}
	
	} else {
		
		$translated_chat_agent_response = 'none';
		$translation_usage = null;
		
	}
	
	
	
	
	
	//------------------------
	// Create the output text
	//------------------------
	// This is sent to the main 
	// web page via Ajax.
	
	
	// Correction (by proofreader_agent) is always being done.
	// If the user did not ask to display the
	// corrected text then setting $corrected_user_message = 'none'
	// causes the correction to not be displayed on the page.
	if ($correction_request != 'selected') {
		
		$corrected_user_message = 'none';
	}
	
	
	$check_array = array(
		'user_message' => $user_message,
		'corrected_user_message' => $corrected_user_message,
		'input_message' => $input_message,
		'uncorrected_user_message' => $uncorrected_user_message,
		'chat_agent_response' => $chat_agent_response, 
		"translated_chat_agent_response" => $translated_chat_agent_response);
	
	
	
	// Dev-only: usage/cost breakdown for this request, per agent, plus a
	// total. Only meaningful when the model/provider returns a "usage"
	// object with cost info (OpenRouter includes this automatically).
	// The frontend keeps this hidden by default - see the
	// #dev-usage-panel div and DEV_MODE flag in index.php.
	$agent_usages = [
		'proofreader' => $proofreader_usage,
		'chat' => $chat_usage,
		'translation' => $translation_usage,
	];
	$total_cost = 0;
	$total_prompt_tokens = 0;
	$total_completion_tokens = 0;
	foreach ($agent_usages as $usage) {
		if (is_array($usage)) {
			$total_cost += $usage['cost'] ?? 0;
			$total_prompt_tokens += $usage['prompt_tokens'] ?? 0;
			$total_completion_tokens += $usage['completion_tokens'] ?? 0;
		}
	}
	$debug_usage = [
		'agents' => $agent_usages,
		'total_cost' => $total_cost,
		'total_prompt_tokens' => $total_prompt_tokens,
		'total_completion_tokens' => $total_completion_tokens,
		'history_pairs' => intdiv(count($message_history), 2),
	];
	
	
	$response = array('success' => true, 
		'check_array' => $check_array,
		'speech_lang_code' => $speech_lang_code,
		'speech_voice_name' => $speech_voice_name,
		'speech_rate' => $speech_rate,
		'check_text' => $user_message,
		'translation_language' => $translation_language, 
		'check_variable' => $mood, 
		'text_to_speak' => $text_to_speak, 
		'speak_status' => $speak_request,
		'chat_text' => $chat_agent_response, 
		'corrected_text' => $corrected_user_message,
		"translated_text" => $translated_chat_agent_response,
		'debug_usage' => $debug_usage);
	
  	echo json_encode($response);
	
	
}


// ============================================================
// Helper functions
// (formerly in php/php_utils_revised.php)
// ============================================================


/**
 * Load configuration from a file
 *
 * @param string $file The configuration file path
 * @return array The parsed configuration
 * @throws Exception if the file does not exist
 */
function load_config($file) {
    if (!file_exists($file)) {
        throw new Exception("Configuration file not found: $file");
    }
    return parse_ini_file($file, true);
}


/**
 * Convert the app's Gemini-style message history (list of
 * ["role" => "user"|"model", "parts" => [["text" => "..."]]])
 * into the OpenAI-compatible "messages" array that OpenRouter expects
 * (list of ["role" => "system"|"user"|"assistant", "content" => "..."]).
 * This keeps the rest of main.php - which builds and stores
 * $message_history in the session - unchanged.
 *
 * @param string $system_message The system message
 * @param array $message_history The Gemini-style message history
 * @return array The OpenAI-style messages array
 */
function build_openrouter_messages($system_message, $message_history) {
    $messages = [];

    $messages[] = ["role" => "system", "content" => $system_message];

    foreach ($message_history as $turn) {
        $role = (isset($turn['role']) && $turn['role'] === 'model') ? 'assistant' : ($turn['role'] ?? 'user');

        $text = '';
        $image_url = null;
        if (isset($turn['parts']) && is_array($turn['parts'])) {
            foreach ($turn['parts'] as $part) {
                if (isset($part['text'])) {
                    $text .= $part['text'];
                }
                if (isset($part['image'])) {
                    // Only one image is ever attached per turn today,
                    // so the last one found wins.
                    $image_url = $part['image'];
                }
            }
        }

        if ($image_url !== null) {
            // Vision-capable turn: content becomes an array of blocks
            // (OpenAI/OpenRouter multimodal format) instead of a plain
            // string. Text-only turns keep the plain-string form below
            // so nothing about existing behavior changes.
            $content = [];
            if ($text !== '') {
                $content[] = ["type" => "text", "text" => $text];
            }
            $content[] = ["type" => "image_url", "image_url" => ["url" => $image_url]];
            $messages[] = ["role" => $role, "content" => $content];
        } else {
            $messages[] = ["role" => $role, "content" => $text];
        }
    }

    return $messages;
}


/**
 * Make an API call to OpenRouter, with retry support
 *
 * @param string $system_message The system message
 * @param array $message_history The message history (Gemini-style; converted internally)
 * @param string $model_id The OpenRouter model id, e.g. "qwen/qwen3.5-flash-02-23"
 * @param string $provider The OpenRouter provider slug, e.g. "alibaba"
 * @param int $max_retries Number of times to retry the API call on failure
 * @return array|string The API response or an error message
 */
function make_api_call($system_message, $message_history, $model_id, $provider, $max_retries = 3) {
    global $path_to_config_ini;
    global $url;
    global $temperature;
    global $max_tokens;
    global $site_url;
    global $site_title;

    $timestamp = date('Y-m-d H:i:s');
    $file_path = "php-errors.log";

    try {
        $config = load_config($path_to_config_ini);
    } catch (Exception $e) {
        error_log($timestamp . ' ' . $e->getMessage(), 3, $file_path);
        return 'Failed to load configuration.';
    }

    $apiKey = $config['api']['API_KEY'] ?? '';
    if (empty($apiKey) || empty($url)) {
        error_log($timestamp . ' API key or URL not configured properly.', 3, $file_path);
        return 'API key or URL not configured properly.';
    }

    $messages = build_openrouter_messages($system_message, $message_history);

    $data = [
        "model" => $model_id,
        // Pins the request to a specific provider on OpenRouter. Setting
        // allow_fallbacks to false means the call will fail rather than
        // silently route to a different (and possibly pricier) provider.
        "provider" => [
            "order" => [$provider],
            "allow_fallbacks" => false
        ],
        "messages" => $messages,
        "temperature" => $temperature,
        "max_tokens" => $max_tokens,
				
		
        "reasoning" => [
            "effort" => "none"
        ],
				
		
    ];
    $headers = [
        "Authorization: Bearer {$apiKey}",
        "Content-Type: application/json",
        "HTTP-Referer: {$site_url}",
        "X-Title: {$site_title}"
    ];

    $attempt = 0;
    while ($attempt < $max_retries) {
        $attempt++;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($curl) ? curl_error($curl) : null;

        curl_close($curl);

        if ($curlError) {
            error_log($timestamp . " Attempt $attempt - cURL error: $curlError\n", 3, $file_path);
        } elseif ($httpStatusCode >= 400) {
            error_log($timestamp . " Attempt $attempt - HTTP error: $httpStatusCode - Response: $result\n", 3, $file_path);
        } else {
            $decodedResult = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Stash usage/cost info for this call so the caller can
                // read it back after the fact. Dev/debug use only - see
                // $GLOBALS['last_api_usage'].
                if (isset($decodedResult['usage'])) {
                    $GLOBALS['last_api_usage'] = $decodedResult['usage'];
                    $GLOBALS['last_api_usage']['model_id'] = $model_id;
                }
                return $decodedResult;
            } else {
                error_log($timestamp . " Attempt $attempt - JSON decode error: " . json_last_error_msg() . "\n", 3, $file_path);
            }
        }

        // Optional: sleep between retries to avoid hitting rate limits
        sleep(1);
    }

    return 'api_error';
}


/**
 * Extract text from an OpenRouter (OpenAI-compatible) API response
 *
 * @param array $response The API response
 * @return string The extracted text or error message
 */
function extract_text_from_response($response) {
    if (isset($response["choices"][0]['message']['content'])) {
        return $response["choices"][0]['message']['content'];
    } elseif (isset($response['error'])) {
        $error_code = $response['error']['code'] ?? '';
        $error_message = $response['error']['message'] ?? 'Unknown error';
        return "Error: " . $error_code . "<br>" . $error_message;
    } else {
        return "Sorry. Something went wrong. Please try again.";
    }
}


/**
 * Run agent without memory
 *
 * @param string $system_message The system message
 * @param string $prompt The prompt
 * @param string $model_id The OpenRouter model id for this agent
 * @param string $provider The OpenRouter provider slug for this agent
 * @return array The output type and text
 */
function run_agent_without_memory($system_message, $prompt, $model_id, $provider) {
    $my_message1 = ["text" => $prompt];
    $parts_list = [$my_message1];
    $message_history = [["role" => "user", "parts" => $parts_list]];

    $response = make_api_call($system_message, $message_history, $model_id, $provider);

    if ($response == "api_error") {
        $response = make_api_call($system_message, $message_history, $model_id, $provider);
    }
	
	
	
	// If the API call failed ten try again (two more trys)
	//----------

    if ($response != "api_error") {
        $response_text = extract_text_from_response($response);
        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }
    }
	
	
	
    if ($response != "api_error") {
        $response_text = extract_text_from_response($response);
        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }
    }
	
	//----------
	
	

    if ($response != "api_error") {
        $response_text = extract_text_from_response($response);
        $output_type = check_output_type($response_text);

        if ($output_type == "is_json_string") {
            $output_text = json_decode($response_text, true);
        } elseif ($output_type == "is_json_object") {
            $response_text = json_encode($response_text);
            $output_text = json_decode($response_text, true);
        } else {
            $output_text = $response_text;
        }

        return [$output_type, $output_text];
    } else {
        return ["is_plain_text", "api_error"];
    }
}


/**
 * Run agent with memory
 *
 * @param string $system_message The system message
 * @param array $message_history The message history
 * @param string $model_id The OpenRouter model id for this agent
 * @param string $provider The OpenRouter provider slug for this agent
 * @return array The output type and text
 */
function run_agent_with_memory($system_message, $message_history, $model_id, $provider) {
    $response = make_api_call($system_message, $message_history, $model_id, $provider);

	
	
	
	// If the API call failed then try again (two more trys)
	//----------
	
    if ($response == "api_error") {
        $response = make_api_call($system_message, $message_history, $model_id, $provider);
    }
	
	
	if ($response == "api_error") {
        $response = make_api_call($system_message, $message_history, $model_id, $provider);
    }
	
	//----------
	
	
	

    if ($response != "api_error") {
		
		
		// If the API call failed then try again (two more trys)
		//----
		
        $response_text = extract_text_from_response($response);
		
        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }
		
		
		$response_text = extract_text_from_response($response);
		
        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }	
		//----
		
    }
	
	
	
	

    if ($response != "api_error") {
        $response_text = extract_text_from_response($response);
        $output_type = check_output_type($response_text);

        if ($output_type == "is_json_string") {
            $output_text = json_decode($response_text, true);
        } elseif ($output_type == "is_json_object") {
            $response_text = json_encode($response_text);
            $output_text = json_decode($response_text, true);
        } else {
            $output_text = $response_text;
        }

        return [$output_type, $output_text];
    } else {
        return ["is_plain_text", "api_error"];
    }
}


/**
 * Check the output type
 *
 * @param mixed $output The output
 * @return string The type of output
 */
function check_output_type($output) {
    if (is_object($output)) {
        return "is_json_object";
    } elseif (is_string($output)) {
        $decoded = json_decode($output, true);
        if ($decoded !== null) {
            return "is_json_string";
        } else {
            return "is_plain_text";
        }
    }
}


/**
 * Convert variable to string
 *
 * @param mixed $variable The variable to convert
 * @return string The variable as a string
 */
function convertToString($variable) {
    if (is_array($variable)) {
        return json_encode($variable);
    } else {
        return (string) $variable;
    }
}


// Function to remove items from a JSON string
// before it gets displayed on the page.
function replaceItemsInString($inputString) {
    $itemsToReplace = array("```", "json", "{", "}", '"correction": "', '"translation": "', "#");
    
    $modifiedString = $inputString;
    foreach ($itemsToReplace as $item) {
        $modifiedString = str_replace($item, "", $modifiedString);
    }
    
    $modifiedString = trim($modifiedString);
    
    // Only strip a trailing quote character if one is actually left over
    // (from removing the opening '"correction": "' / '"translation": "'
    // prefix above, e.g. `"correction": "Hello world"` -> `Hello world"`).
    // NOTE: this used to be an unconditional substr($modifiedString, 0, -1),
    // which chopped the last character off EVERY message - including
    // plain-text (non-JSON) proofreader replies that have no trailing quote
    // to remove. That's why "hello" was coming back as "hell", and why the
    // '---' no-errors marker was coming back as '--' (see the '--' handling
    // a few lines below - that was a workaround for this bug, not a real
    // model quirk).
    if (substr($modifiedString, -1) === '"') {
        $modifiedString = substr($modifiedString, 0, -1);
    }
    
    $modifiedString = removeEmojis($modifiedString);
    
    return $modifiedString;
}

// Function to remove emojis from text
function removeEmojis($text) {
    $emojiPatterns = array(
        '/[\x{1F600}-\x{1F64F}]/u',  // Emoticons
        '/[\x{1F300}-\x{1F5FF}]/u',  // Miscellaneous Symbols and Pictographs
        '/[\x{1F680}-\x{1F6FF}]/u',  // Transport and Map Symbols
        '/[\x{1F700}-\x{1F77F}]/u',  // Alchemical Symbols
        '/[\x{1F780}-\x{1F7FF}]/u',  // Geometric Shapes Extended
        '/[\x{1F800}-\x{1F8FF}]/u',  // Supplemental Arrows-C
        '/[\x{1F900}-\x{1F9FF}]/u',  // Supplemental Symbols and Pictographs
        '/[\x{1FA00}-\x{1FA6F}]/u',  // Chess Symbols
        '/[\x{1FA70}-\x{1FAFF}]/u',  // Symbols and Pictographs Extended-A
        '/[\x{2600}-\x{26FF}]/u',    // Miscellaneous Symbols
        '/[\x{2700}-\x{27BF}]/u',    // Dingbats
        '/[\x{FE00}-\x{FE0F}]/u',    // Variation Selectors
        '/[\x{1F1E6}-\x{1F1FF}]/u',  // Flags
    );

    foreach ($emojiPatterns as $pattern) {
        $text = preg_replace($pattern, '', $text);
    }

    return $text;
}

?>
