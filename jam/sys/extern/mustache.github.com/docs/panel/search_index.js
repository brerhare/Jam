var search_data = {"index":{"longSearchIndex":["lib/mustache.rb","mustache","mustache","mustache","mustache","mustache::parser","mustache","mustache::sinatra","mustache","lib/rack/bug/panels/mustache_panel/mustache_extension.rb","lib/rack/bug/panels/mustache_panel.rb","rack","rack::bug","rack::bug::mustachepanel","mustache","mustache::context","object","mustache","mustache::context","mustache","mustache::generator","mustache::parser","mustache::template","mustache::generator","mustache::sinatra::helpers","mustache","mustache","rack::bug::mustachepanel","mustache","mustache::parser","mustache::parser","mustache::generator","mustache::context","mustache::context","rack::bug::mustachepanel","mustache::sinatra::helpers","mustache::sinatra::helpers","mustache::context","rack::bug::mustachepanel","mustache::context","mustache::generator","mustache::parser","mustache::parser::syntaxerror","mustache::template","mustache::generator","mustache::generator","mustache::generator","mustache::generator","mustache::generator","mustache::parser","mustache","mustache","mustache::context","mustache","mustache","mustache::context","mustache::parser","mustache::context","mustache","mustache","mustache","mustache::parser","mustache::sinatra","mustache","mustache","mustache::template","object","mustache","mustache","rack::bug::mustachepanel","mustache::parser","mustache::parser","mustache::parser","mustache::generator","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","mustache","rack::bug::mustachepanel","rack::bug::mustachepanel::view","mustache","mustache","mustache::parser::syntaxerror","mustache::template","mustache","mustache","mustache::template","mustache","mustache::context","rack::bug::mustachepanel","rack::bug::mustachepanel::view","mustache","mustache","mustache","mustache","mustache","files/contributors.html","files/history_md.html","files/license.html","files/readme_md.html","files/readme_md.html","files/lib/mustache_rb.html","files/lib/mustache/context_rb.html","files/lib/mustache/generator_rb.html","files/lib/mustache/parser_rb.html","files/lib/mustache/sinatra_rb.html","files/lib/mustache/template_rb.html","files/lib/mustache/version_rb.html","files/lib/rack/bug/panels/mustache_panel_rb.html","files/lib/rack/bug/panels/mustache_panel/mustache_extension_rb.html","files/lib/rack/bug/panels/mustache_panel/view_mustache.html"],"info":[["Mustache","lib/mustache.rb","classes/Mustache.html"," < Object","Mustache is the base class from which your Mustache subclasses should inherit (though it can be used",1],["Context","Mustache","classes/Mustache/Context.html"," < Object","A Context represents the context which a Mustache template is executed within. All Mustache tags reference",1],["ContextMiss","Mustache","classes/Mustache/ContextMiss.html"," < RuntimeError","A ContextMiss is raised whenever a tag's target can not be found in the current context if `Mustache#raise_on_context_miss?`",1],["Generator","Mustache","classes/Mustache/Generator.html"," < Object","The Generator is in charge of taking an array of Mustache tokens, usually assembled by the Parser, and",1],["Parser","Mustache","classes/Mustache/Parser.html"," < Object","The Parser is responsible for taking a string template and converting it into an array of tokens and,",1],["SyntaxError","Mustache::Parser","classes/Mustache/Parser/SyntaxError.html"," < StandardError","A SyntaxError is raised when the Parser comes across unclosed tags, sections, illegal content in tags,",1],["Sinatra","Mustache","classes/Mustache/Sinatra.html"," < ","Support for Mustache in your Sinatra app. require 'mustache/sinatra' class Hurl < Sinatra::Base register",1],["Helpers","Mustache::Sinatra","classes/Mustache/Sinatra/Helpers.html"," < ","",1],["Template","Mustache","classes/Mustache/Template.html"," < Object","A Template represents a Mustache template. It compiles and caches a raw string template into something",1],["Object","lib/rack/bug/panels/mustache_panel/mustache_extension.rb","classes/Object.html"," < Object","",1],["Rack","lib/rack/bug/panels/mustache_panel.rb","classes/Rack.html"," < ","",1],["Bug","Rack","classes/Rack/Bug.html"," < ","",1],["MustachePanel","Rack::Bug","classes/Rack/Bug/MustachePanel.html"," < Panel","MustachePanel is a Rack::Bug panel which tracks the time spent rendering Mustache views as well as all",1],["View","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel/View.html"," < Mustache","The view is responsible for rendering our panel. While Rack::Bug takes care of the nav, the content rendered",1],["[]","Mustache","classes/Mustache.html#M000079","(key)","Context accessors. view = Mustache.new view[:name] = \"Jon\" view.template = \"Hi, {{name}}!\" view.render",2],["[]","Mustache::Context","classes/Mustache/Context.html#M000007","(name)","Alias for `fetch`. ",2],["[]","Object","classes/Object.html#M000044","(name)","",2],["[]=","Mustache","classes/Mustache.html#M000080","(key, value)","",2],["[]=","Mustache::Context","classes/Mustache/Context.html#M000006","(name, value)","Can be used to add a value to the context in a hash-like way. context[:name] = \"Chris\" ",2],["classify","Mustache","classes/Mustache.html#M000071","(underscored)","template_partial => TemplatePartial ",2],["compile","Mustache::Generator","classes/Mustache/Generator.html#M000009","(exp)","Given an array of tokens, returns an interpolatable Ruby string. ",2],["compile","Mustache::Parser","classes/Mustache/Parser.html#M000025","(template)","Given a string template, returns an array of tokens. ",2],["compile","Mustache::Template","classes/Mustache/Template.html#M000038","(src = @source)","Does the dirty work of transforming a Mustache template into an interpolation-friendly Ruby string. ",2],["compile!","Mustache::Generator","classes/Mustache/Generator.html#M000012","(exp)","Given an array of tokens, converts them into Ruby code. In particular there are three types of expressions",2],["compile_mustache","Mustache::Sinatra::Helpers","classes/Mustache/Sinatra/Helpers.html#M000030","(view, options = {})","Given a view name and settings, finds and prepares an appropriate view class for this view. ",2],["compiled?","Mustache","classes/Mustache.html#M000070","()","Has this instance or its class already compiled a template? ",2],["compiled?","Mustache","classes/Mustache.html#M000069","()","Has this template already been compiled? Compilation is somewhat expensive so it may be useful to check",2],["content","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000091","()","The content of our Rack::Bug panel ",2],["context","Mustache","classes/Mustache.html#M000078","()","A helper method which gives access to the context at a given time. Kind of a hack for now, but useful",2],["ctag","Mustache::Parser","classes/Mustache/Parser.html#M000024","()","The closing tag delimiter. This too may be changed at runtime. ",2],["error","Mustache::Parser","classes/Mustache/Parser.html#M000035","(message, pos = position)","Raises a SyntaxError. The message should be the name of the error - other details such as line number",2],["ev","Mustache::Generator","classes/Mustache/Generator.html#M000018","(s)","An interpolation-friendly version of a string, for use within a Ruby string. ",2],["fetch","Mustache::Context","classes/Mustache/Context.html#M000011","(name, default = :__raise)","Similar to Hash#fetch, finds a value by `name` in the context's stack. You may specify the default return",2],["has_key?","Mustache::Context","classes/Mustache/Context.html#M000010","(key)","Do we know about a particular key? In other words, will calling `context[key]` give us a result that",2],["heading","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000090","()","The string used for our tab in Rack::Bug's navigation bar ",2],["mustache","Mustache::Sinatra::Helpers","classes/Mustache/Sinatra/Helpers.html#M000027","(template, options={}, locals={})","Call this in your Sinatra routes. ",2],["mustache_class","Mustache::Sinatra::Helpers","classes/Mustache/Sinatra/Helpers.html#M000028","(template, options)","Returns a View class for a given template name. ",2],["mustache_in_stack","Mustache::Context","classes/Mustache/Context.html#M000002","()","Find the first Mustache in the stack. If we're being rendered inside a Mustache object as a context,",2],["name","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000089","()","The name of this Rack::Bug panel ",2],["new","Mustache::Context","classes/Mustache/Context.html#M000000","(mustache)","Expect to be passed an instance of `Mustache`. ",2],["new","Mustache::Generator","classes/Mustache/Generator.html#M000008","(options = {})","Options are unused for now but may become useful in the future. ",2],["new","Mustache::Parser","classes/Mustache/Parser.html#M000022","(options = {})","Accepts an options hash which does nothing but may be used in the future. ",2],["new","Mustache::Parser::SyntaxError","classes/Mustache/Parser/SyntaxError.html#M000020","(message, position)","",2],["new","Mustache::Template","classes/Mustache/Template.html#M000036","(source)","Expects a Mustache template as a string along with a template path, which it uses to find partials. ",2],["on_etag","Mustache::Generator","classes/Mustache/Generator.html#M000017","(name)","An escaped tag. ",2],["on_inverted_section","Mustache::Generator","classes/Mustache/Generator.html#M000014","(name, content)","Fired when we find an inverted section. Just like `on_section`, we're passed the inverted section name",2],["on_partial","Mustache::Generator","classes/Mustache/Generator.html#M000015","(name)","Fired when the compiler finds a partial. We want to return code which calls a partial at runtime instead",2],["on_section","Mustache::Generator","classes/Mustache/Generator.html#M000013","(name, content)","Callback fired when the compiler finds a section token. We're passed the section name and the array of",2],["on_utag","Mustache::Generator","classes/Mustache/Generator.html#M000016","(name)","An unescaped tag. ",2],["otag","Mustache::Parser","classes/Mustache/Parser.html#M000023","()","The opening tag delimiter. This may be changed at runtime. ",2],["partial","Mustache","classes/Mustache.html#M000049","(name)","Override this in your subclass if you want to do fun things like reading templates from a database. It",2],["partial","Mustache","classes/Mustache.html#M000048","(name)","Given a name, attempts to read a file and return the contents as a string. The file is not rendered,",2],["partial","Mustache::Context","classes/Mustache/Context.html#M000001","(name)","A {{>partial}} tag translates into a call to the context's `partial` method, which would be this sucker",2],["path","Mustache","classes/Mustache.html#M000052","()","Alias for `template_path` ",2],["path=","Mustache","classes/Mustache.html#M000053","(path)","Alias for `template_path` ",2],["pop","Mustache::Context","classes/Mustache/Context.html#M000005","()","Removes the most recently added object from the context's internal stack. Returns the Context. ",2],["position","Mustache::Parser","classes/Mustache/Parser.html#M000032","()","Returns [lineno, column, line] ",2],["push","Mustache::Context","classes/Mustache/Context.html#M000003","(new)","Adds a new object to the context's internal stack. Returns the Context. ",2],["raise_on_context_miss=","Mustache","classes/Mustache.html#M000068","(boolean)","",2],["raise_on_context_miss?","Mustache","classes/Mustache.html#M000077","()","Instance level version of `Mustache.raise_on_context_miss?` ",2],["raise_on_context_miss?","Mustache","classes/Mustache.html#M000067","()","Should an exception be raised when we cannot find a corresponding method or key in the current context?",2],["regexp","Mustache::Parser","classes/Mustache/Parser.html#M000034","(thing)","Used to quickly convert a string into a regular expression usable by the string scanner. ",2],["registered","Mustache::Sinatra","classes/Mustache/Sinatra.html#M000033","(app)","Called when you `register Mustache::Sinatra` in your Sinatra app. ",2],["render","Mustache","classes/Mustache.html#M000042","(*args)","Helper method for quickly instantiating and rendering a view. ",2],["render","Mustache","classes/Mustache.html#M000081","(data = template, ctx = {})","Parses our fancy pants template file and returns normal file with all special {{tags}} and {{#sections}}replaced{{/sections}}.",2],["render","Mustache::Template","classes/Mustache/Template.html#M000037","(context)","Renders the `@source` Mustache template using the given `context`, which should be a simple hash keyed",2],["render","Object","classes/Object.html#M000041","(*args, &block)","",2],["render_file","Mustache","classes/Mustache.html#M000046","(name, context = {})","Given a file name and an optional context, attempts to load and render the file as a template. ",2],["render_file","Mustache","classes/Mustache.html#M000047","(name, context = {})","Given a file name and an optional context, attempts to load and render the file as a template. ",2],["reset","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000086","()","Clear out our page load-specific variables. ",2],["scan_tags","Mustache::Parser","classes/Mustache/Parser.html#M000026","()","Find {{mustaches}} and add them to the @result array. ",2],["scan_text","Mustache::Parser","classes/Mustache/Parser.html#M000029","()","Try to find static text, e.g. raw HTML with no {{mustaches}}. ",2],["scan_until_exclusive","Mustache::Parser","classes/Mustache/Parser.html#M000031","(regexp)","Scans the string until the pattern is matched. Returns the substring *excluding* the end of the match,",2],["str","Mustache::Generator","classes/Mustache/Generator.html#M000019","(s)","",2],["template","Mustache","classes/Mustache.html#M000060","()","The template is the actual string Mustache uses as its template. There is a bit of magic here: what we",2],["template","Mustache","classes/Mustache.html#M000075","()","The template can be set at the instance level. ",2],["template=","Mustache","classes/Mustache.html#M000061","(template)","",2],["template=","Mustache","classes/Mustache.html#M000076","(template)","",2],["template_extension","Mustache","classes/Mustache.html#M000054","()","A Mustache template's default extension is 'mustache' ",2],["template_extension=","Mustache","classes/Mustache.html#M000055","(template_extension)","",2],["template_file","Mustache","classes/Mustache.html#M000058","()","The template file is the absolute path of the file Mustache will use as its template. By default it's",2],["template_file=","Mustache","classes/Mustache.html#M000059","(template_file)","",2],["template_name","Mustache","classes/Mustache.html#M000056","()","The template name is the Mustache template file without any extension or other information. Defaults",2],["template_name=","Mustache","classes/Mustache.html#M000057","(template_name)","",2],["template_path","Mustache","classes/Mustache.html#M000050","()","The template path informs your Mustache subclass where to look for its corresponding template. By default",2],["template_path=","Mustache","classes/Mustache.html#M000051","(path)","",2],["templateify","Mustache","classes/Mustache.html#M000073","(obj)","Turns a string into a Mustache::Template. If passed a Template, returns it. ",2],["templateify","Mustache","classes/Mustache.html#M000074","(obj)","",2],["times","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000087","()","The view render times for this page load ",2],["times","Rack::Bug::MustachePanel::View","classes/Rack/Bug/MustachePanel/View.html#M000084","()","We track the render times of all the Mustache views on this page load. ",2],["to_html","Mustache","classes/Mustache.html#M000043","(*args)","Alias for `render` ",2],["to_html","Mustache","classes/Mustache.html#M000082","(data = template, ctx = {})","Alias for #render",2],["to_s","Mustache::Parser::SyntaxError","classes/Mustache/Parser/SyntaxError.html#M000021","()","",2],["to_s","Mustache::Template","classes/Mustache/Template.html#M000039","(src = @source)","Alias for #compile",2],["to_text","Mustache","classes/Mustache.html#M000083","(data = template, ctx = {})","Alias for #render",2],["to_text","Mustache","classes/Mustache.html#M000045","(*args)","Alias for `render` ",2],["tokens","Mustache::Template","classes/Mustache/Template.html#M000040","(src = @source)","Returns an array of tokens for a given template. ",2],["underscore","Mustache","classes/Mustache.html#M000072","(classified = name)","TemplatePartial => template_partial Takes a string but defaults to using the current class' name. ",2],["update","Mustache::Context","classes/Mustache/Context.html#M000004","(new)","Alias for #push",2],["variables","Rack::Bug::MustachePanel","classes/Rack/Bug/MustachePanel.html#M000088","()","The variables used on this page load ",2],["variables","Rack::Bug::MustachePanel::View","classes/Rack/Bug/MustachePanel/View.html#M000085","()","Any variables used in this page load are collected and displayed. ",2],["view_class","Mustache","classes/Mustache.html#M000066","(name)","When given a symbol or string representing a class, will try to produce an appropriate view class. e.g.",2],["view_namespace","Mustache","classes/Mustache.html#M000062","()","The constant under which Mustache will look for views. By default it's `Object`, but it might be nice",2],["view_namespace=","Mustache","classes/Mustache.html#M000063","(namespace)","",2],["view_path","Mustache","classes/Mustache.html#M000064","()","Mustache searches the view path for .rb files to require when asked to find a view class. Defaults to",2],["view_path=","Mustache","classes/Mustache.html#M000065","(path)","",2],["CONTRIBUTORS","files/CONTRIBUTORS.html","files/CONTRIBUTORS.html","","* Chris Wanstrath * Francesc Esplugas * Magnus Holm * Nicolas Sanguinetti * Nathan Weizenbaum * Jan-Erik",3],["HISTORY.md","files/HISTORY_md.html","files/HISTORY_md.html","","## 0.10.0 (2010-04-02)  * Added Inverted Sections (^). See mustache(5) for details. * Added Template#source",3],["LICENSE","files/LICENSE.html","files/LICENSE.html","","Copyright (c) 2009 Chris Wanstrath  Permission is hereby granted, free of charge, to any person obtaining",3],["README.md","files/README_md.html","files/README_md.html","","Mustache =========  Inspired by [ctemplate][1] and [et][2], Mustache is a framework-agnostic way to render",3],["README.md","files/README_md.html","files/README_md.html","","Mustache =========  Inspired by [ctemplate][1] and [et][2], Mustache is a framework-agnostic way to render",3],["mustache.rb","files/lib/mustache_rb.html","files/lib/mustache_rb.html","","",3],["context.rb","files/lib/mustache/context_rb.html","files/lib/mustache/context_rb.html","","",3],["generator.rb","files/lib/mustache/generator_rb.html","files/lib/mustache/generator_rb.html","","",3],["parser.rb","files/lib/mustache/parser_rb.html","files/lib/mustache/parser_rb.html","","",3],["sinatra.rb","files/lib/mustache/sinatra_rb.html","files/lib/mustache/sinatra_rb.html","","",3],["template.rb","files/lib/mustache/template_rb.html","files/lib/mustache/template_rb.html","","",3],["version.rb","files/lib/mustache/version_rb.html","files/lib/mustache/version_rb.html","","",3],["mustache_panel.rb","files/lib/rack/bug/panels/mustache_panel_rb.html","files/lib/rack/bug/panels/mustache_panel_rb.html","","",3],["mustache_extension.rb","files/lib/rack/bug/panels/mustache_panel/mustache_extension_rb.html","files/lib/rack/bug/panels/mustache_panel/mustache_extension_rb.html","","",3],["view.mustache","files/lib/rack/bug/panels/mustache_panel/view_mustache.html","files/lib/rack/bug/panels/mustache_panel/view_mustache.html","","<script type=\"text/javascript\"> $(function() {   $('#mustache_variables .variable').each(function() {",3]],"searchIndex":["mustache","context","contextmiss","generator","parser","syntaxerror","sinatra","helpers","template","object","rack","bug","mustachepanel","view","[]()","[]()","[]()","[]=()","[]=()","classify()","compile()","compile()","compile()","compile!()","compile_mustache()","compiled?()","compiled?()","content()","context()","ctag()","error()","ev()","fetch()","has_key?()","heading()","mustache()","mustache_class()","mustache_in_stack()","name()","new()","new()","new()","new()","new()","on_etag()","on_inverted_section()","on_partial()","on_section()","on_utag()","otag()","partial()","partial()","partial()","path()","path=()","pop()","position()","push()","raise_on_context_miss=()","raise_on_context_miss?()","raise_on_context_miss?()","regexp()","registered()","render()","render()","render()","render()","render_file()","render_file()","reset()","scan_tags()","scan_text()","scan_until_exclusive()","str()","template()","template()","template=()","template=()","template_extension()","template_extension=()","template_file()","template_file=()","template_name()","template_name=()","template_path()","template_path=()","templateify()","templateify()","times()","times()","to_html()","to_html()","to_s()","to_s()","to_text()","to_text()","tokens()","underscore()","update()","variables()","variables()","view_class()","view_namespace()","view_namespace=()","view_path()","view_path=()","contributors","history.md","license","readme.md","readme.md","mustache.rb","context.rb","generator.rb","parser.rb","sinatra.rb","template.rb","version.rb","mustache_panel.rb","mustache_extension.rb","view.mustache"]}}