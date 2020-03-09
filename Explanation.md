# Explanation of the "Internal Crawling" plugin project

The problem to be solved with this plugin was to help the administrators of the website by giving them visibility on how the homepage is linked internally with the rest of the website. Basically this plugin creates a list with all the internal links in the homepage and helps the administrator to understand how the hompage is interacting with the rest of the website and at the same time it helps to evaluate and look for ways to improve the SEO rankings.


I solved this problem with a technical planning and architecture to develop a plugin that is easy to use. 

Regarding the project planning, I created a [Trello board](https://trello.com/b/EskPKGEs) where I created all the relevant cards (issues) to complete this project. That way I made sure that every requirement was fulfilled. I added some unique IDs to each card so I can follow some naming standards on my branches.

On the technical side, I approached it with an OOP methodology and some procedural coding for small functions. The files structure was another challenge, as there are no standard ways to scaffold pluggins. So I created 6 main directories:

* assets (styles and scripts for both admin and public pages).
* classes (project common classes).
* inc (specific classes and functions).
* languages.
* Tests.
* views.

My main idea was to separate logic from presentation, so everything related to templates, partials, etc. is located in the `views` folder.

I also installed NPM and Grunt for the frontend tasks and builds.

On the business logic side, I created a class in `inc/classes/` called `class-internal-crawling-plugin.php` that is the entrypoint to all the plugin logic.

The classes and functions names are self-explanatory so it helps to understand what does each one of them.

I was also inspired by the WP Media plugins code, so many parts of this plugin's code are based on the WP Rocket and Imagify plugins.

And finally, I tried to develop this plugin in a way that it can escalate and maintain.

In terms of code quality and standards, I run the phpcs inspection always before pushing my code to the repository, and also installed Travis CI on my GitHub repo to make sure all my PRs passed the requirements.

Unfortunatelly I couldn't add unit or integration tests, but definitelly it's something I'm gonna do in the next days.
