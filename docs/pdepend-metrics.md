Class Dependency
M_AFFERENT_COUPLING          = 'ca',
M_EFFERENT_COUPLING          = 'ce';

Class Level
M_IMPLEMENTED_INTERFACES       = 'impl',
M_CLASS_INTERFACE_SIZE         = 'cis',
M_CLASS_SIZE                   = 'csz',
M_NUMBER_OF_PUBLIC_METHODS     = 'npm',
M_PROPERTIES                   = 'vars',
M_PROPERTIES_INHERIT           = 'varsi',
M_PROPERTIES_NON_PRIVATE       = 'varsnp',
M_WEIGHTED_METHODS             = 'wmc',
M_WEIGHTED_METHODS_INHERIT     = 'wmci',
M_WEIGHTED_METHODS_NON_PRIVATE = 'wmcnp';

Code Rank
M_CODE_RANK         = 'cr',
M_REVERSE_CODE_RANK = 'rcr';

Cohesion
M_LCOM4  = 'lcom4';

Coupling
M_CALLS  = 'calls',
M_FANOUT = 'fanout',
M_CA     = 'ca',
M_CBO    = 'cbo',
M_CE     = 'ce';

Crap Index
M_CRAP_INDEX = 'crap',
M_COVERAGE = 'cov';

Cyclomatic Complexity
M_CYCLOMATIC_COMPLEXITY_1 = 'ccn',
M_CYCLOMATIC_COMPLEXITY_2 = 'ccn2';

Dependency
M_NUMBER_OF_CLASSES          = 'tc',
M_NUMBER_OF_CONCRETE_CLASSES = 'cc',
M_NUMBER_OF_ABSTRACT_CLASSES = 'ac',
M_AFFERENT_COUPLING          = 'ca',
M_EFFERENT_COUPLING          = 'ce',
M_ABSTRACTION                = 'a',
M_INSTABILITY                = 'i',
M_DISTANCE                   = 'd';

Halstead Metrics
M_HALSTEAD_LENGTH = 'hnt', // N = N1 + N2 (total operators + operands)
M_HALSTEAD_VOCABULARY = 'hnd', // n = n1 + n2 (distinct operators + operands)
M_HALSTEAD_VOLUME = 'hv', // V = N * log2(n)
M_HALSTEAD_DIFFICULTY = 'hd', // D = (n1 / 2) * (N2 / n2)
M_HALSTEAD_LEVEL = 'hl', // L = 1 / D
M_HALSTEAD_EFFORT = 'he', // E = V * D
M_HALSTEAD_TIME = 'ht', // T = E / 18
M_HALSTEAD_BUGS = 'hb', // B = (E ** (2/3)) / 3000
M_HALSTEAD_CONTENT = 'hi'; // I = (V / D)

Hierarchy
M_NUMBER_OF_ABSTRACT_CLASSES = 'clsa',
M_NUMBER_OF_CONCRETE_CLASSES = 'clsc',
M_NUMBER_OF_ROOT_CLASSES     = 'roots',
M_NUMBER_OF_LEAF_CLASSES     = 'leafs';

Inheritance
M_AVERAGE_NUMBER_DERIVED_CLASSES = 'andc',
M_AVERAGE_HIERARCHY_HEIGHT       = 'ahh',
M_DEPTH_OF_INHERITANCE_TREE      = 'dit',
M_NUMBER_OF_ADDED_METHODS        = 'noam',
M_NUMBER_OF_OVERWRITTEN_METHODS  = 'noom',
M_NUMBER_OF_DERIVED_CLASSES      = 'nocc',
M_MAXIMUM_INHERITANCE_DEPTH      = 'maxDIT';

Maintainablility Index
M_MAINTAINABILITY_INDEX = 'mi';

NPath Complexity
M_NPATH_COMPLEXITY = 'npath';

Node Count
M_NUMBER_OF_PACKAGES   = 'nop',
M_NUMBER_OF_CLASSES    = 'noc',
M_NUMBER_OF_INTERFACES = 'noi',
M_NUMBER_OF_METHODS    = 'nom',
M_NUMBER_OF_FUNCTIONS  = 'nof';

Node LoC
M_LINES_OF_CODE             = 'loc',
M_COMMENT_LINES_OF_CODE     = 'cloc',
M_EXECUTABLE_LINES_OF_CODE  = 'eloc',
M_LOGICAL_LINES_OF_CODE     = 'lloc',
M_NON_COMMENT_LINES_OF_CODE = 'ncloc';
