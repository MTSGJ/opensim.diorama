// vim: set tabstop=4 paste nocindent noautoindent ff=unix: 

/*

 ex) ./read_srtm -lat 36.1 -lon 138.9 -h 256 -w 256 -dat /home/apache/gsigo/SRTM3


*/

#include "read_srtm.h"


int LineSize = 3601;		// for SRTM1
int FileSize = 12967201;	// 3601*3601


int main(int argc, char** argv)
{
	Buffer dtpath = make_Buffer_bystr("srtm");

	sWord* map_height;
	float lat, lon;

	int hsize = 256;
	int wsize = 256;
	int resln = 1;
	//
	int i;
	for (i=1; i<argc; i++) {
		if (!strcmp(argv[i],"-lat")) {if (i!=argc-1) lat   = atof(argv[i+1]);}
		if (!strcmp(argv[i],"-lon")) {if (i!=argc-1) lon   = atof(argv[i+1]);}
		if (!strcmp(argv[i],"-hsz")) {if (i!=argc-1) hsize = atoi(argv[i+1]);}
		if (!strcmp(argv[i],"-wsz")) {if (i!=argc-1) wsize = atoi(argv[i+1]);}
		if (!strcmp(argv[i],"-wsz")) {if (i!=argc-1) wsize = atoi(argv[i+1]);}
		if (!strcmp(argv[i],"-res")) {if (i!=argc-1) resln = atoi(argv[i+1]);}
		if (!strcmp(argv[i],"-dat")) {if (i!=argc-1) copy_s2Buffer(argv[i+1], &dtpath);}
	}

	if (resln==3) {			// for SRTM3
		LineSize = 1201;
		FileSize = 1442401;	// 1201*1201
	}

	map_height = (sWord*)malloc(wsize*hsize*sizeof(sWord));
	if (map_height==NULL) {
		print_message("No more memory!! (%d)\n", wsize*hsize*sizeof(sWord));
		exit(1);
	}
	for (i=0; i<wsize*hsize; i++) map_height[i] = SRTM_NON_LEVEL;

	read_height (map_height, wsize, hsize, lat, lon, (char*)dtpath.buf);
	print_height(map_height, wsize, hsize);

	free(map_height);
	free_Buffer(&dtpath);

	return 0;
}


void  read_height(sWord* map_height, int wsize, int hsize, float lat, float lon, char* path)
{
	int latn = (int)lat;
	int lonn = (int)lon;

	if (lat<0.0) latn--;
	if (lon<0.0) lonn--;

	lat = lat - latn;
	lon = lon - lonn;

	int sw = (int)(lon*(LineSize-1));					// 0 - 1199
	int sh = (LineSize-1) - (int)(lat*(LineSize-1));	// 0 - 1199
	int ew = sw + wsize;
	int eh = sh + hsize;
	int mw = Min(ew, LineSize-1);
	int mh = Min(eh, LineSize-1);

	int i, j;
	sWord* hgt = read_hgt_file(latn, lonn, path);
	if (hgt!=NULL) {
		for (j=sh; j<mh; j++) {
			int mapj = (j-sh)*wsize;
			int hgtj = j*LineSize;
			for (i=sw; i<mw; i++) {
				map_height[mapj + i - sw] = hgt[hgtj + i];
			}
		}
		free(hgt);
	}

	if (ew>=LineSize) {
		hgt = read_hgt_file(latn, lonn+1, path);
		if (hgt!=NULL) {
			for (j=sh; j<mh; j++) {
				int mapj = (j-sh)*wsize;
				int hgtj = j*LineSize;
				for (i=0; i<ew-LineSize+1; i++) {
					map_height[mapj + i + mw - sw] = hgt[hgtj + i];
				}
			}
			free(hgt);
		}
	}

	if (eh>=LineSize) {
		hgt = read_hgt_file(latn-1, lonn, path);
		if (hgt!=NULL) {
			for (j=0; j<eh-LineSize+1; j++) {
				int mapj = (j+mh-sh)*wsize;
				int hgtj = j*LineSize;
				for (i=sw; i<mw; i++) {
					map_height[mapj + i - sw] = hgt[hgtj + i];
				}
			}
			free(hgt);
		}
	}

	if (ew>=LineSize && eh>=LineSize) {
		hgt = read_hgt_file(latn-1, lonn+1, path);
		if (hgt!=NULL) {
			for (j=0; j<eh-LineSize+1; j++) {
				int mapj = (j+mh-sh)*wsize;
				int hgtj = j*LineSize;
				for (i=0; i<ew-LineSize+1; i++) {
					map_height[mapj + i + mw - sw] = hgt[hgtj + i];
				}
			}
			free(hgt);
		}
	}
}


sWord* read_hgt_file(int latn, int lonn, char* path)
{
	int  i;
	char fname[256];
	char latc = 'N';
	char lonc = 'E';

	if (latn<0) {
		latc = 'S';
		latn = - latn;
	}
	if (lonn<0) {
		lonc = 'W';
		lonn = - lonn;
	}
	//
	if (lonc=='W' && lonn>180) {
		lonc = 'E';
		lonn = 360 - lonn;
	}
	else if (lonc=='E' && lonn>=180) {
		lonc = 'W';
		lonn = 360 - lonn;
	}

	int dtsz = sizeof(sWord);
	sWord* hgt = (sWord*)malloc(FileSize*dtsz);
	if (hgt==NULL) return NULL;
	for (i=0; i<FileSize; i++) hgt[i] = SRTM_NON_LEVEL;

	snprintf(fname, 255, SRTM_FNAME_FORMAT, path, latc, latn, lonc, lonn);
	FILE* fp = fopen(fname, "rb");
	if (fp==NULL) return hgt;
	fread((void*)hgt, FileSize*dtsz, 1, fp);
	fclose(fp);

	ntoh_ar(hgt, FileSize*dtsz);
	return hgt;
}


void  print_height(sWord* map_height, int wsize, int hsize)
{
	int  i, j;

	for (j=0; j<hsize; j++) {
		int mapj = j*wsize;
		for (i=0; i<wsize; i++) {
			int height = map_height[mapj + i];
			printf("%07d ", height);
		}
		printf("\n");
	}
}

