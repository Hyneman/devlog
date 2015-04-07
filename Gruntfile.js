//
//// DEV/LOG: ISSUE TRACKING SYSTEM FOR DEVELOPERS.
//// COPYRIGHT (C) 2015 BY RICO BETI <rico.beti@silentbyte.com>.
////
//// THIS FILE IS PART OF DEV/LOG.
////
//// DEV/LOG IS FREE SOFTWARE: YOU CAN REDISTRIBUTE IT AND/OR MODIFY IT
//// UNDER THE TERMS OF THE GNU GENERAL PUBLIC LICENSE AS PUBLISHED BY
//// THE FREE SOFTWARE FOUNDATION, EITHER VERSION 3 OF THE LICENSE, OR
//// (AT YOUR OPTION) ANY LATER VERSION.
////
//// DEV/LOG IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL,
//// BUT WITHOUT ANY WARRANTY; WITHOUT EVEN THE IMPLIED WARRANTY OF
//// MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. SEE THE
//// GNU GENERAL PUBLIC LICENSE FOR MORE DETAILS.
////
//// YOU SHOULD HAVE RECEIVED A COPY OF THE GNU GENERAL PUBLIC LICENSE
//// ALONG WITH DEV/LOG. IF NOT, SEE <http://www.gnu.org/licenses/>.
//

'use strict';

var LIVERELOAD_PORT = 35729;
var lrSnippet = require('connect-livereload')({port: LIVERELOAD_PORT});
var mountFolder = function (connect, dir) {
	return connect.static(require('path').resolve(dir));
};

module.exports = function (grunt) {
	require('time-grunt')(grunt);
	require('load-grunt-tasks')(grunt);

	var yeomanConfig = {
		app: 'app',
		dist: 'dist'
	};

	grunt.initConfig({
		yeoman: yeomanConfig,
		watch: {
			config: {
				files: '<%= yeoman.app %>/config/**/**',
				tasks: [
					'copy:config',
					'mergeJson:devlogConfig'
				]
			},
			api: {
				files: '<%= yeoman.app %>/api/**/**',
				tasks: [
					'copy:api',
					'shell:deployApi'
				]
			},
			install: {
				files: '<%= yeoman.app %>/install/**/**',
				tasks: [
					'copy:install'
				]
			},
			emberTemplates: {
				files: '<%= yeoman.app %>/templates/**/*.hbs',
				tasks: ['emberTemplates']
			},
			compass: {
				files: ['<%= yeoman.app %>/styles/{,*/}*.{scss,sass}'],
				tasks: ['compass:server']
			},
			neuter: {
				files: ['<%= yeoman.app %>/scripts/{,*/}*.js'],
				tasks: ['neuter']
			},
			livereload: {
				options: {
					livereload: LIVERELOAD_PORT
				},
				files: [
					'.tmp/scripts/*.js',
					'<%= yeoman.app %>/*.html',
					'{.tmp,<%= yeoman.app %>}/styles/{,*/}*.css',
					'<%= yeoman.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
				]
			}
		},
		connect: {
			options: {
				port: 9000,
				hostname: '0.0.0.0'
			},
			livereload: {
				options: {
					middleware: function (connect) {
						return [
							lrSnippet,
							mountFolder(connect, '.tmp'),
							mountFolder(connect, yeomanConfig.app)
						];
					}
				}
			},
			test: {
				options: {
					middleware: function (connect) {
						return [
							mountFolder(connect, 'test'),
							mountFolder(connect, '.tmp')
						];
					}
				}
			},
			dist: {
				options: {
					middleware: function (connect) {
						return [
							mountFolder(connect, yeomanConfig.dist)
						];
					}
				}
			}
		},
		open: {
			server: {
				path: 'http://localhost:<%= connect.options.port %>'
			}
		},
		clean: {
			dist: {
				files: [{
					dot: true,
					src: [
						'.tmp',
						'<%= yeoman.dist %>/*',
						'!<%= yeoman.dist %>/.git*'
					]
				}]
			},
			server: '.tmp'
		},
		jshint: {
			options: {
				jshintrc: '.jshintrc',
				reporter: require('jshint-stylish')
			},
			all: [
				'Gruntfile.js',
				'<%= yeoman.app %>/scripts/{,*/}*.js',
				'!<%= yeoman.app %>/scripts/vendor/*',
				'test/spec/{,*/}*.js'
			]
		},
		mocha: {
			all: {
				options: {
					run: true,
					urls: ['http://localhost:<%= connect.options.port %>/index.html']
				}
			}
		},
		compass: {
			options: {
				sassDir: '<%= yeoman.app %>/styles',
				cssDir: '.tmp/styles',
				generatedImagesDir: '.tmp/images/generated',
				imagesDir: '<%= yeoman.app %>/images',
				javascriptsDir: '<%= yeoman.app %>/scripts',
				fontsDir: '<%= yeoman.app %>/styles/fonts',
				importPath: '<%= yeoman.app %>/bower_components',
				httpImagesPath: '/images',
				httpGeneratedImagesPath: '/images/generated',
				httpFontsPath: '/styles/fonts',
				relativeAssets: false
			},
			dist: {},
			server: {
				options: {
					debugInfo: true
				}
			}
		},
		rev: {
			dist: {
				files: {
					src: [
						'<%= yeoman.dist %>/scripts/{,*/}*.js',
						'<%= yeoman.dist %>/styles/{,*/}*.css',
						'<%= yeoman.dist %>/images/{,*/}*.{png,jpg,jpeg,gif,webp}',
						'<%= yeoman.dist %>/styles/fonts/*'
					]
				}
			}
		},
		useminPrepare: {
			html: '.tmp/index.html',
			options: {
				dest: '<%= yeoman.dist %>'
			}
		},
		usemin: {
			html: ['<%= yeoman.dist %>/{,*/}*.html'],
			css: ['<%= yeoman.dist %>/styles/{,*/}*.css'],
			options: {
				dirs: ['<%= yeoman.dist %>']
			}
		},
		imagemin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>/images',
					src: '{,*/}*.{png,jpg,jpeg}',
					dest: '<%= yeoman.dist %>/images'
				}]
			}
		},
		svgmin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>/images',
					src: '{,*/}*.svg',
					dest: '<%= yeoman.dist %>/images'
				}]
			}
		},
		cssmin: {
			dist: {
				files: {
					'<%= yeoman.dist %>/styles/main.css': [
						'.tmp/styles/{,*/}*.css',
						'<%= yeoman.app %>/styles/{,*/}*.css'
					]
				}
			}
		},
		htmlmin: {
			dist: {
				options: {
					//
				},
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>',
					src: '*.html',
					dest: '<%= yeoman.dist %>'
				}]
			}
		},
		replace: {
			app: {
				options: {
					variables: {
					ember: 'bower_components/ember/ember.js',
					ember_data: 'bower_components/ember-data/ember-data.js',
					devlog_config: 'config-dev.js'
				}
				},
				files: [
					{
						src: '<%= yeoman.app %>/index.html', dest: '.tmp/index.html'
					}
				]
			},
			dist: {
				options: {
					variables: {
						ember: 'bower_components/ember/ember.prod.js',
						ember_data: 'bower_components/ember-data/ember-data.prod.js',
						devlog_config: 'config-dist.js'
					}
				},
				files: [
					{
						src: '<%= yeoman.app %>/index.html',
						dest: '.tmp/index.html'
					}
				]
			}
		},
		shell: {
			deployApi: {
				command: 'composer install && rm composer.json composer.lock',
				options: {
					execOptions: {
						cwd: '<%= yeoman.dist %>/api/'
					}
				}
			}
		},
		mergeJson: {
			devlogConfig: {
				src: [
					'<%= yeoman.app %>/config/devlog.json',
					'<%= yeoman.app %>/config/devlog-dev.json'
				],
				dst: '<%= yeoman.dist %>/config/devlog.json'
			}
		},
		chmod: {
			logs: {
				files : [
					{
						'filename': '<%= yeoman.dist %>/api/logs',
						'mode': '777'
					}
				]
			}
		},
		copy: {
			config: {
				files: [
					{
						expand: true,
						dot: true,
						cwd: '<%= yeoman.app %>/config/',
						dest: '<%= yeoman.dist %>/config/',
						src: [
							'**/**',
							'!devlog-dev.json',
						]
					}
				]
			},
			api: {
				files: [
					{
						expand: true,
						dot: true,
						cwd: '<%= yeoman.app %>/api/',
						dest: '<%= yeoman.dist %>/api/',
						src: [
							'**/**',
							'!**/*.gitignore',
							'!vendor/**'
						]
					}
				]
			},
			install : {
				files: [
					{
						expand: true,
						dot: true,
						cwd: '<%= yeoman.app %>/install/',
						dest: '<%= yeoman.dist %>/install/',
						src: [
							'**/**'
						]
					}
				]
			},
			fonts: {
				files: [
					{
						expand: true,
						flatten: true,
						filter: 'isFile',
						cwd: '<%= yeoman.app %>/bower_components/',
						dest: '<%= yeoman.app %>/styles/fonts/',
						src: [
							'bootstrap-sass-official/assets/fonts/bootstrap/**',
							'components-font-awesome/fonts/**'
						]
					}
				]
			},
			dist: {
				files: [
					{
						expand: true,
						dot: true,
						cwd: '<%= yeoman.app %>',
						dest: '<%= yeoman.dist %>',
						src: [
							'*.{ico,txt}',
							'.htaccess',
							'images/{,*/}*.{webp,gif}',
							'styles/fonts/*',
							'!styles/fonts/*.gitignore'
						]
					}
				]
			}
		},
		concurrent: {
			server: [
				'emberTemplates',
				'compass:server'
			],
			test: [
				'emberTemplates',
				'compass'
			],
			dist: [
				'emberTemplates',
				'compass:dist',
				'imagemin',
				'svgmin',
				'htmlmin'
			]
		},
		emberTemplates: {
			options: {
				templateName: function (sourceFile) {
					var templatePath = yeomanConfig.app + '/templates/';
					return sourceFile.replace(templatePath, '');
				}
			},
			dist: {
				files: {
					'.tmp/scripts/compiled-templates.js': '<%= yeoman.app %>/templates/**/*.hbs'
				}
			}
		},
		neuter: {
			app: {
				options: {
					filepathTransform: function (filepath) {
						return yeomanConfig.app + '/' + filepath;
					}
				},
				src: '<%= yeoman.app %>/scripts/app.js',
				dest: '.tmp/scripts/combined-scripts.js'
			}
		}
	});

	grunt.registerMultiTask('mergeJson', 'Merge devlog.json and devlog-dev.json fles', function() {
		var extend = function(target, base) {
			target = target || {};
			for(var attribute in base) {
				var current = base[attribute];
				switch(typeof current) {
					case 'object':
						target[attribute] = extend(target[attribute], current);
						break;
					case 'string':
						target[attribute] = current;
						break;
					default:
						grunt.fail.warn("mergeJson needs to be modified in order to"
							+ " support JSON types other than 'string' and 'object'.");
				}
			}
			return target;
		};

		grunt.log.writeln('Merging ' + JSON.stringify(this.data.src)
			+ ' into ' + "'" + this.data.dst + "'.");

		var json = {};
		for(var file in this.data.src) {
			var current = grunt.file.readJSON(this.data.src[file]);
			extend(json, current);
		}

		grunt.file.write(this.data.dst, JSON.stringify(json, null, '\t'));
	});

	grunt.registerMultiTask('chmod', 'Set required permissions for files and directories.', function() {
		var fs = require('fs');
		var files = this.data.files;
		for(var i = 0; i < files.length; i++) {
			var file = files[i];
			if(!file.filename || !file.mode) {
				grunt.fail.warn('File definition for CHMOD is missing arguments.');
			}

			try {
				fs.chmodSync(file.filename, file.mode);
			}
			catch(e) {
				grunt.fail.warn('Could not CHMOD ' + file.mode + ' file ' + file.filename);
			}
		}
	})

	grunt.registerTask('server', function (target) {
		grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
		grunt.task.run(['serve:' + target]);
	});

	grunt.registerTask('serve', function (target) {
		if (target === 'dist') {
			return grunt.task.run(['build', 'open', 'connect:dist:keepalive']);
		}

		grunt.task.run([
			'clean:server',
			'replace:app',
			'concurrent:server',
			'neuter:app',
			'copy:fonts',
			'mergeJson:devlogConfig',
			'chmod:logs',
			'connect:livereload',
			'open',
			'watch'
		]);
	});

	grunt.registerTask('test', [
		'clean:server',
		'replace:app',
		'concurrent:test',
		'connect:test',
		'neuter:app',
		'mocha'
	]);

	grunt.registerTask('build', [
		'clean:dist',
		'replace:dist',
		'useminPrepare',
		'concurrent:dist',
		'neuter:app',
		'concat',
		'cssmin',
		'uglify',
		'copy',
		'rev',
		'usemin',
		'shell:deployApi',
		'mergeJson:devlogConfig',
		'chmod:logs'
	]);

	grunt.registerTask('default', [
		'jshint',
		'test',
		'build'
	]);
};
