/**
*/

#include "tools.h"
#include "buffer.h"
#include "math.h"


#define  SRTM_NON_LEVEL 	-20
#define  SRTM_FNAME_FORMAT 	"%s/%c%02d%c%03d.hgt"


sWord* read_hgt_file(int latn, int lonn, char* path);
void   read_height (sWord* map_height, int wsize, int hsize, float lat, float lon, char* path);
void   print_height(sWord* map_height, int wsize, int hsize);

